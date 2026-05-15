let gulp = require('gulp'),
    concat = require('gulp-concat')
    uglify = require('gulp-uglify');

gulp.task('wp-enqueue-scripts', function () {
    gulp.src([
        './dist/app.js',
        './Components/CustomCssClasses/js/custom-css-classes.js',
    ])
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('dist'));
});

gulp.task('default', gulp.series(['wp-enqueue-scripts']));