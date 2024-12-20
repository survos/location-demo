const $ = require('jquery');
window.jQuery = window.$ = global.jQuery = global.$ = $;

require('bootstrap');
require('../js/us-states');
require('datamaps/dist/datamaps.world.min');
require('@popperjs/core');
require('../../vendor/survos/base-bundle/assets/js/adminlte');
require('../../vendor/tetranz/select2entity-bundle/Resources/public/js/select2entity.js');
// start the Stimulus application
import '../bootstrap';

require('select2'); // base library, not select2entity

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

import('jstree/dist/themes/default/style.min.css');
require('jstree');

$('.jstree').jstree();

$('#jstree').jstree({

});

$('#jstree').on("changed.jstree", function (e, data) {
    console.log(data.selected);
});

/**
\u0040fortawesome\/fontawesome\u002Dfree
bootstrap
fontawesome
jquery
popper.js
**/
