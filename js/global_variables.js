/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(function() {
  var global_variables = {};
  $global_variable = {
    set: function(key, value) {
      global_variables[key] = value;
    },
    get: function(key) {
      return global_variables[key];
    },
    listAll: function() {
      return global_variables;
    },
    /**
     * removes the property from global variable object
     * @param {string} variable_name
     * @returns {bool} true
     */
    remove: function(variable_name) {
      delete global_variables[variable_name];
    }
  };
});


