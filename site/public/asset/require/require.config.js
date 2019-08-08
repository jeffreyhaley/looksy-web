requirejs.config({
    "baseUrl": "./",
    "paths": {
      "modules": "../modules",

      //underscore: '../../public/asset/underscore/underscore-min',
      widget: './common/widget/core/view/js/model/widget.core.bo'
      //moment: '../../public/asset/momentjs/moment.min'
      
    }
});

// Load the main app module to start the app
requirejs(["public/js/core.min"]);