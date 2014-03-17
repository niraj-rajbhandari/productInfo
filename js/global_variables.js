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
    }
  };
});


