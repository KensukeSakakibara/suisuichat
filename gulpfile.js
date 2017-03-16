'use strict';

var gulp = require('gulp');
var phpunit = require('gulp-phpunit');
//var uglify = require('gulp-uglify');
//var rename = require('gulp-rename');


gulp.task('default', function(){
    gulp.watch(['src/**/**/*.php'], ['phpunit']);
    gulp.watch(['tests/Functional/*.php'], ['phpunit']);
    //gulp.watch(['module/Application/js/*.js'], ['uglify']);
});

gulp.task("phpunit", function(){
    gulp.src('phpunit.xml').pipe(phpunit())
});

/*
gulp.task("uglify", function(){
    gulp.src('module/Application/js/*.js')
        .pipe(uglify())
        .pipe(rename({suffix:'.min'}))
        .pipe(gulp.dest('public/js'));
});
*/
