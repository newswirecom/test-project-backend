require('./bootstrap');

import Vue from 'vue';

(function() {

    // Register top level components
    Vue.component('jobs-table', require('./components/JobsTable.vue').default);

    window.app = new Vue({
        el: '#vue',
    });

})();
