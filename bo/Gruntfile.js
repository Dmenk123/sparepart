module.exports = (grunt) => {
    /**
    * Project configuration.
    */
    grunt.registerTask('code-minify', 'Minify CSS code and JS code.', () => {

        /**
        * Load NPM tasks.
        */
        grunt.loadNpmTasks('grunt-contrib-cssmin')
        grunt.loadNpmTasks('grunt-contrib-uglify')

        /**
        * Create configuration.
        */
        grunt.initConfig({

            /**
            * Minify CSS code.
            */
            cssmin: {
                options: {
                    level: { 1: { specialComments:0 } }
                },
                main: {
                    files: [{
                        expand:true,
                        cwd:'/assets/build/css',
                        src:'style.css',
                        dest:'_dist/css',
                        ext:'.css'
                    }]
                }
            },

            /**
            * Minify JS code.
            */
            uglify: {
                main: {
                    files: [{
                        expand:true,
                        cwd:'assets/js_module',
                        src:['*.js'],
                        dest:'build/js',
                        ext:'.js'
                    }]
                }
            }
        })

        /**
        * Run tasks.
        */
        grunt.task.run(['cssmin', 'uglify'])
    })
}