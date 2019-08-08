module.exports = function(grunt) {

var sitePath = '../../';
		
  grunt.initConfig({
    concat: {
  			site: {
				src: [
					sitePath + 'module/core/presentation/js/controller/MCoreController.js',
					sitePath + 'module/core/presentation/js/*.js',
					sitePath + 'module/**/*.js',
					sitePath + 'framework/widget/core/**/*.js',
					sitePath + 'framework/**/*.js'
					],
				dest: sitePath + 'public/js/core.min.js'
			}
    },
    less: {
     site: {
				src: [
					sitePath + 'module/core/presentation/less/import.less',
					sitePath + 'module/**/less/*.less'
					],
				dest: sitePath + 'public/css/core.css',
				options: {
					compress: false,
					yuicompress: false,
					paths: [sitePath + 'module/core/presentation/less/']
				}
			}
    }
  });

  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-concat');

  grunt.registerTask('site', ['concat', 'less']);

  grunt.registerTask('default', ['concat', 'less']);

};