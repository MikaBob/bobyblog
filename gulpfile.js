"use strict";

const gulp = require("gulp");   // la base
const plumber = require("gulp-plumber"); // prevent gulp"s pipe breaking
const browser_sync = require("browser-sync").create(); // upload les fichiers sans avoir à recharger la page
const sass = require("gulp-sass");  // génération de css à partir de scss
const rename = require("gulp-rename");
const cssnano = require("cssnano"); // minify css
const postcss = require("gulp-postcss"); // rétro-compatibilité de css pour vieux navigateur
const autoprefixer = require("autoprefixer"); // ajoute les règles css de chaque navigateur (-webkit-gradient, -webkit-transition, etc...)
const eslint = require("gulp-eslint"); // js code checker
const uglify = require("gulp-uglify");
const del = require("del");

const TMP_DIR = "./var/tmp";
const OUTPOUT_DIR = "./public/scripts/";

function browserSync(done) {
    browser_sync.init({
        proxy: "localhost"
    });
    done();
}

function clean() {
  return del([TMP_DIR]);
}

function css() {
    return gulp
        .src("./src/Scss/**/*.scss")
        .pipe(plumber())
        .pipe(rename({ suffix: ".min" }))
        .pipe(sass({ outputStyle: "expanded" }))
        .pipe(postcss([autoprefixer(), cssnano()]))
        .pipe(gulp.dest(OUTPOUT_DIR))
        .pipe(browser_sync.stream());
}

function scriptsLint() {
    return gulp
        .src(["./src/Js/**/*"])
        .pipe(plumber())
//        .pipe(eslint({fix: true}))
//        .pipe(eslint.format())
        .pipe(gulp.dest(TMP_DIR))
//        .pipe(eslint.failAfterError());
}

function scripts() {
    return gulp
        .src([TMP_DIR + "/**/*"])
        .pipe(plumber())
        .pipe(rename({ suffix: ".min" }))
//        .pipe(uglify({mangle:{toplevel: true}}))
        .pipe(gulp.dest(OUTPOUT_DIR))
        .pipe(browser_sync.stream());
}

function copyDependencies() {
    return gulp
        .src([
            "node_modules/bootstrap/dist/js/bootstrap.bundle.min.js",
            "node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js",
            "node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css",
            "node_modules/jquery/dist/jquery.min.js",
            "node_modules/@popperjs/core/dist/umd/popper.min.js",
            "node_modules/photoswipe/dist/**/*",
            "!node_modules/photoswipe/dist/photoswipe.js",
            "!node_modules/photoswipe/dist/photoswipe-ui-default.js"
        ])
        .pipe(plumber())
        .pipe(gulp.dest(OUTPOUT_DIR))
        .pipe(browser_sync.stream());
}

function watchFiles() {
    gulp.watch("./src/Scss/**/*", css);
    gulp.watch("./src/Js/**/*", gulp.series(clean, scriptsLint, scripts));
}


const watch = gulp.parallel(watchFiles, browserSync);
const js = gulp.series(clean, scriptsLint, scripts, copyDependencies);

exports.css = css;
exports.js = js;
exports.cpd = copyDependencies;
exports.watch = watch;