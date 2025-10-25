const { parallel, src, dest, watch } = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const terser = require("gulp-terser");
const sourcemaps = require("gulp-sourcemaps");

const paths = {
  scss_globals: [
    "./styles/**/*.scss",
    "!./styles/page-templates/**",
  ],
  scss_templates: "./styles/page-templates/*.scss",
  scss_blocks: "./blocks/**/*.scss",
  js: "./js/**/*.js",
  js_blocks: "./blocks/**/*.js",
};

function buildStyles() {
  return src(paths.scss_globals)
    .pipe(sass({ style: "compressed" }).on("error", sass.logError))
    .pipe(sourcemaps.write())
    .pipe(dest("./styles/"));
}
function buildStylesTemplates() {
  return src(paths.scss_templates)
    .pipe(sass().on("error", sass.logError))
    .pipe(sourcemaps.write())
    .pipe(dest("./styles/page-templates/"));
}
function buildStylesBlocks() {
  return src(paths.scss_blocks)
    .pipe(sass().on("error", sass.logError))
    .pipe(sourcemaps.write())
    .pipe(dest("./blocks/"));
}

function buildScripts() {
 return src(paths.js)
   .pipe(sourcemaps.init())
   .pipe(terser())
   .pipe(sourcemaps.write("./js/**/"))
   .pipe(dest("./js/**/"));
}

function buildScriptsBlocks() {
 return src(paths.js_blocks)
   .pipe(sourcemaps.init())
   .pipe(terser())
   .pipe(sourcemaps.write("./blocks/**/"))
   .pipe(dest("./blocks/**/"));
}

function watchFiles() {
  watch(paths.scss_globals, buildStyles);
  watch(paths.scss_templates, buildStylesTemplates);
  watch(paths.scss_blocks, buildStylesBlocks);
  watch(paths.js, buildScripts);
  watch(paths.js_blocks, buildScriptsBlocks);
}

exports.default = parallel(buildStyles, buildStylesTemplates, buildStylesBlocks, buildScripts, buildScriptsBlocks, watchFiles);
