var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var minify = require('gulp-minify-css');
var ngAnnotate = require('gulp-ng-annotate');
var argv = require('yargs').argv;
var gulpif = require('gulp-if');
var replace = require('gulp-replace');
var rename = require('gulp-rename');
var del = require('del');

gulp.task('js', function () {
    gulp.src([
            'components/jquery/dist/jquery.min.js',
            'components/jquery-mask-plugin/dist/jquery.mask.min.js',
            'components/bootstrap/dist/js/bootstrap.min.js',

            'components/moment/min/moment.min.js',
            'components/angular/angular.js',
            'components/angular-bootstrap/ui-bootstrap.min.js',
            'components/angular-bootstrap/ui-bootstrap-tpls.min.js',
            'components/angular-ui-router/release/angular-ui-router.min.js',
            'components/amitava82-angular-multiselect/dist/multiselect.js',
            'components/Chart.js/Chart.min.js',
            'components/angular-chart.js/dist/angular-chart.min.js',

            'js/app.js', 
            'js/*.js',
            'js/controllers/*.js'
        ])
        .pipe(concat('app.js'))
        .pipe(ngAnnotate())
        .pipe(gulpif(argv.production, uglify()))
        .pipe(gulp.dest('./dist/js/'));
});

gulp.task('css', function () {
    gulp.src([
            'components/bootstrap/dist/css/bootstrap.min.css',
            'components/font-awesome/css/font-awesome.min.css',
            'components/amitava82/angular-multiselect/dist/multiselect.css',
            'components/angular-chart.js/dist/angular-chart.min.css',
            'css/lato.font.css',
            'css/style.css'
        ])
        .pipe(concat('styles.css'))
        .pipe(minify({keepBreaks: true}))
        .pipe(gulp.dest('./dist/css/'));
});

gulp.task('cachebust', function() {
    del('index.html');

    gulp.src('./index.tpl.html')
        .pipe(replace(/styles.css\?v=(.+?)"/g, 'styles.css?v=' + Math.random() + '"'))
        .pipe(replace(/app.js\?v=(.+?)"/g, 'app.js?v=' + Math.random() + '"'))
        .pipe(rename('index.html'))
        .pipe(gulp.dest('.'));
});

gulp.task('fonts', function() {
    gulp.src([
            'components/font-awesome/fonts/*',
            'components/Ionicons/fonts/*',
            'components/bootstrap/fonts/*'
        ])
        .pipe(gulp.dest('./dist/fonts/'));
});

gulp.task('watch', ['js'], function () {
    gulp.watch('js/**/*.js', ['js']);
    gulp.watch('css/**/*.css', ['css']);
});

gulp.task('default', ['css', 'js', 'cachebust', 'fonts']);
