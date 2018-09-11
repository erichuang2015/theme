module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    sass: {
      // compiles specific scss files and puts them inside assets/css
      dist: {
        options: {
          style: 'expanded'
        },
        files: {
          // 'destination': 'source'
          'assets/css/theme.css': 'src/scss/theme.scss', 
          'assets/css/editor-style.css': 'src/scss/editor-style.scss'
        }
      }
    },
    cssmin: {
      // minifies all css files in assets/css
      dist: {
        files: [{
          expand: true,
          cwd: 'assets/css',
          src: ['*.css', '!*.min.css'],
          dest: 'assets/css',
          ext: '.min.css'
        }]
      }
    },
    postcss: {
      options: {
        map: true, // inline sourcemaps

        processors: [
          require('autoprefixer')({browsers: 'last 2 versions'}), // add vendor prefixes
        ]
      },
      // adds vendor prefixes for all css files in assets/css
      dist: {
        src: 'assets/css/*.css'
      }
    },
    concat: {
      // merges specific js files into assets/js/theme.js
      theme: {
        src: [
          'src/js/utilities.js',
          'src/js/scroll-to.js',
          'src/js/navbar.js',
          'src/js/common.js',
        ],
        dest: 'assets/js/theme.js',
      }
    },
    uglify:
    {
      options: {
        compress: {
          drop_console: true
        }
      },
      // minifies all js files inside assets/js
      dist: {
        files: [{
          expand: true,
          cwd: 'assets/js',
          src: ['**/*.js', '!**/*.min.js'],
          dest: 'assets/js',
          rename: function (dst, src) {
            return dst + '/' + src.replace('.js', '.min.js');
          }
        }]
      }
    },
    copy: {
      // copies files inside src/images/ to assets/images/
      images: {
        expand: true,
        cwd: 'src/images/',
        src: '**',
        dest: 'assets/images/',
      },
      // copies files inside src/fonts/ to assets/fonts/
      fonts: {
        expand: true,
        cwd: 'src/fonts/',
        src: '**',
        dest: 'assets/fonts/',
      },
      js: {
        files: [
          { src: ['src/js/mce-plugins.js'], dest: 'assets/js/mce-plugins.js' },
        ],
      },
      // copies specific vendor files to assets folder
      vendor: {
        files: [
          { src: ['vendor/bootstrap/dist/js/bootstrap.js'], dest: 'assets/js/bootstrap.js' },
          { src: ['vendor/html5shiv/dist/html5shiv.js'], dest: 'assets/js/html5shiv.js' },
          { src: ['node_modules/popper.js/dist/umd/popper.js'], dest: 'assets/js/popper.js' },
          { src: ['vendor/jquery.scrollto/jquery.scrollTo.js'], dest: 'assets/js/jquery.scrollTo.js' },
          { src: ['vendor/sticky-kit/jquery.sticky-kit.js'], dest: 'assets/js/jquery.sticky-kit.js' },
          { src: ['vendor/fancybox/dist/jquery.fancybox.js'], dest: 'assets/js/jquery.fancybox.js' },
          { src: ['vendor/fancybox/dist/jquery.fancybox.css'], dest: 'assets/css/jquery-fancybox.css' }, // css min needs '-'
          { src: ['vendor/owl.carousel/dist/owl.carousel.js'], dest: 'assets/js/owl.carousel.js' },
          { src: ['vendor/owl.carousel/dist/assets/owl.carousel.css'], dest: 'assets/css/owl-carousel.css' } // css min needs '-'
        ],
      },
    },
    watch: {
      css: {
        files: 'src/**/*.scss',
        tasks: ['sass', 'postcss', 'cssmin'],
        options: {
          livereload: true,
        },
      },
      scripts: {
        files: ['src/js/**/*.js'],
        tasks: [ 'concat', 'copy', 'uglify'],
        options: {
          interrupt: true
        }
      }
    }
  });

  // tasks.
  grunt.loadNpmTasks( 'grunt-contrib-sass' );
  grunt.loadNpmTasks( 'grunt-postcss' );
  grunt.loadNpmTasks( 'grunt-contrib-uglify' );
  grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
  grunt.loadNpmTasks( 'grunt-contrib-concat' );
  grunt.loadNpmTasks( 'grunt-contrib-watch' );
  grunt.loadNpmTasks( 'grunt-contrib-copy' );

  // Default task(s).
  grunt.registerTask( 'default', ['watch'] );

  // run: `grunt dist` to built assets folder
  grunt.registerTask( 'dist', [
    'sass:dist',        // compiles src/scss to assets/css
    'postcss:dist',     // adds vendor prefixes
    'copy:images',      // copies src/images to assets/images 
    'copy:fonts',       // copies src/fonts to assets/fonts 
    'copy:js',          // copies specific JavaScript files 
    'copy:vendor',      // copies vendors to assets/
    'concat:theme',     // creates 1 theme js file in assets/js
    'cssmin:dist',      // minifies css files in assets/css
    'uglify:dist'       // minifies js files in assets/js
  ]);
};