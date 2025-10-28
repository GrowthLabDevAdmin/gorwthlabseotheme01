const { parallel, src, dest, watch } = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const terser = require("gulp-terser");
const sourcemaps = require("gulp-sourcemaps");
const rename = require("gulp-rename"); // Añade esta línea

const paths = {
  scss_globals: ["./styles/**/*.scss", "!./styles/page-templates/**"],
  scss_blocks: "./blocks/**/*.scss",
  js: [
    "./js/**/*.js",
    "!./js/**/*-min.js", // Excluir archivos ya minificados
    "!./js/**/*.min.js", // Por si hay archivos con .min.js
  ],
  js_blocks: [
    "./blocks/**/*.js",
    "!./blocks/**/*-min.js",
    "!./blocks/**/*.min.js",
  ],
};

function buildStyles() {
  return src(paths.scss_globals)
    .pipe(sass({ style: "compressed" }).on("error", sass.logError))
    .pipe(
      rename(function (path) {
        path.basename += "-min";
      })
    )
    .pipe(sourcemaps.init())
    .pipe(sourcemaps.write("./"))
    .pipe(dest("./styles/"));
}
function buildStylesBlocks() {
  return src(paths.scss_blocks)
    .pipe(sass({ style: "compressed" }).on("error", sass.logError))
    .pipe(
      rename(function (path) {
        path.basename += "-min";
      })
    )
    .pipe(sourcemaps.init())
    .pipe(sourcemaps.write("./"))
    .pipe(dest("./blocks/"));
}

function buildScripts() {
  return src(paths.js)
    .pipe(terser())
    .pipe(
      rename(function (path) {
        path.basename += "-min";
      })
    )
    .pipe(sourcemaps.init())
    .pipe(sourcemaps.write("./"))
    .pipe(dest("./js/"));
}

function buildScriptsBlocks() {
  return src(paths.js_blocks)
    .pipe(terser())
    .pipe(
      rename(function (path) {
        path.basename += "-min";
      })
    )
    .pipe(sourcemaps.init())
    .pipe(sourcemaps.write("./"))
    .pipe(dest("./blocks/"));
}

function watchFiles() {
  watch(paths.scss_globals, buildStyles);
  watch(paths.scss_blocks, buildStylesBlocks);
  watch(paths.js, buildScripts);
  watch(paths.js_blocks, buildScriptsBlocks);
}

exports.default = parallel(
  buildStyles,
  buildStylesBlocks,
  buildScripts,
  buildScriptsBlocks,
  watchFiles
);
