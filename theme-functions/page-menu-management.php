<?php

/**
 * Verificar si un post ya existe en un menú
 */
function growthlabtheme01_post_in_menu($post_id, $menu_id)
{
    $menu_items = wp_get_nav_menu_items($menu_id);

    if (!$menu_items) {
        return false;
    }

    foreach ($menu_items as $item) {
        if (
            $item->object_id === $post_id &&
            (
                $item->type === 'post_type' ||
                ($item->object === 'page') ||
                ($item->object === 'post')
            )
        ) {
            return $item->ID;
        }
    }

    return false;
}

/**
 * Añade/actualiza un post/página en múltiples menús.
 * Recibe un array de entradas: [ ['menu' => ID, 'menu_item_label' => 'Etiqueta'], ... ]
 *
 * @param int $post_id
 * @param array $menu_entries
 * @return array Resultado con 'success' y 'added' => [ menu_id => menu_item_id, ... ]
 */
function add_page_to_menus($post_id, $menu_entries = [])
{
    $post_id = absint($post_id);
    if (!$post_id) {
        return ['success' => false, 'message' => __('Post ID inválido', 'growthlabtheme01')];
    }

    $post = get_post($post_id);
    if (!$post) {
        return ['success' => false, 'message' => __('El post/página no existe.', 'growthlabtheme01')];
    }

    $added = [];

    foreach ($menu_entries as $entry) {
        // Normalizar entry
        if (is_array($entry)) {
            $menu_id = isset($entry['menu']) ? absint($entry['menu']) : 0;
            $label   = isset($entry['menu_item_label']) ? sanitize_text_field($entry['menu_item_label']) : '';
        } else {
            // si solo se pasa un ID
            $menu_id = absint($entry);
            $label = '';
        }

        if (!$menu_id) {
            continue;
        }

        $menu_obj = wp_get_nav_menu_object($menu_id);
        if (!$menu_obj) {
            continue;
        }

        // Comprobar si ya existe en este menú
        $existing_item_id = growthlabtheme01_post_in_menu($post_id, $menu_id);

        $use_label = $label === '' ? $post->post_title : $label;

        $menu_item_args = [
            'menu-item-type' => 'post_type',
            'menu-item-object' => $post->post_type,
            'menu-item-object-id' => $post_id,
            'menu-item-title' => sanitize_text_field($use_label),
            'menu-item-status' => 'publish',
        ];

        if ($existing_item_id) {
            // Actualizar item existente
            $menu_item_id = wp_update_nav_menu_item($menu_id, $existing_item_id, $menu_item_args);
        } else {
            // Crear nuevo item
            $menu_item_id = wp_update_nav_menu_item($menu_id, 0, $menu_item_args);
        }

        if (!is_wp_error($menu_item_id)) {
            $added[$menu_id] = $menu_item_id;
        } else {
            error_log('[growthlab] Error al procesar menú ' . $menu_id . ': ' . $menu_item_id->get_error_message());
        }
    }

    if (empty($added)) {
        return ['success' => false, 'message' => __('No se añadió la página a ningún menú.', 'growthlabtheme01')];
    }

    return ['success' => true, 'added' => $added];
}

/**
 * Remueve items de menú asociados a un post, excepto los menús indicados.
 *
 * @param int $post_id
 * @param array|int $keep_menu_ids Menús a conservar (array o ID único). Si vacío o 0 -> elimina de todos.
 */
function growthlabtheme01_remove_post_from_other_menus($post_id, $keep_menu_ids = [])
{
    $post_id = absint($post_id);
    if (!$post_id) return;

    // Normalizar keep_menu_ids a array de ints
    if (!is_array($keep_menu_ids)) {
        $keep_menu_ids = $keep_menu_ids ? [absint($keep_menu_ids)] : [];
    }
    $keep_menu_ids = array_filter(array_map('absint', $keep_menu_ids));

    $items = get_posts([
        'post_type' => 'nav_menu_item',
        'meta_query' => [
            [
                'key' => '_menu_item_object_id',
                'value' => $post_id,
                'compare' => '='
            ]
        ],
        'posts_per_page' => -1
    ]);

    if (empty($items)) return;

    foreach ($items as $item) {
        $terms = wp_get_post_terms($item->ID, 'nav_menu');
        $menu_term_id = (!empty($terms) && !is_wp_error($terms)) ? intval($terms[0]->term_id) : 0;

        if ($menu_term_id && in_array($menu_term_id, $keep_menu_ids, true)) {
            // Conservar este item
            continue;
        }

        wp_delete_post($item->ID, true);
    }
}

/**
 * Sincroniza el post/página con los menús introducidos en el repeater ACF `add_page_to_menus`.
 * Cada fila del repeater debe contener al menos:
 *  - subfield 'menu' (ID del menú)
 *  - subfield 'menu_item_label' (opcional)
 *
 * Ejecuta al guardar el post.
 */
function growthlabtheme01_sync_menu_on_save($post_id, $post, $update)
{
    // No correr en autosaves, revisiones ni si el usuario no puede editar
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Leer el repeater: 'add_page_to_menus'
    $rows = get_field('add_page_to_menus', $post_id);

    // Normalizar filas a entries: [ ['menu'=>ID,'menu_item_label'=>'...'], ... ]
    $entries = [];
    if (is_array($rows) && !empty($rows)) {
        foreach ($rows as $row) {
            $menu_id = 0;
            $label = '';

            if (is_array($row)) {
                if (isset($row['menu'])) {
                    $menu_id = absint($row['menu']);
                } elseif (isset($row['add_page_to_menu'])) {
                    $menu_id = absint($row['add_page_to_menu']);
                }

                if (isset($row['menu_item_label'])) {
                    $label = sanitize_text_field($row['menu_item_label']);
                } elseif (isset($row['menu_item_label_custom'])) {
                    $label = sanitize_text_field($row['menu_item_label_custom']);
                }
            }

            if ($menu_id) {
                $entries[] = ['menu' => $menu_id, 'menu_item_label' => $label];
            }
        }
    }

    if (!empty($entries)) {
        // Extraer IDs para conservar
        $keep_ids = array_map(function ($e) {
            return intval($e['menu']);
        }, $entries);

        // Añadir/actualizar en cada menú objetivo
        $res = add_page_to_menus($post_id, $entries);

        if (isset($res['success']) && $res['success']) {
            // Eliminar copias en menús no objetivo
            growthlabtheme01_remove_post_from_other_menus($post_id, $keep_ids);
        } else {
            error_log('[growthlab] add_page_to_menus error: ' . ($res['message'] ?? 'unknown'));
        }
    } else {
        // Si no hay filas -> eliminar de todos los menús
        growthlabtheme01_remove_post_from_other_menus($post_id, []);
    }
}
add_action('save_post', 'growthlabtheme01_sync_menu_on_save', 20, 3);
