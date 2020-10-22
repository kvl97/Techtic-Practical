"use strict";
// Class definition
let editor;

var KTCkeditor = function () {    
    // Private functions
    var demos = function () {
        ClassicEditor
			.create( document.querySelector( '#kt-ckeditor-1' ) )
			.then( function(editor){
				console.log(editor);
			})
			.catch(function(error) {
				//console.error( error );
			} );

		ClassicEditor
			.create( document.querySelector( '#kt-ckeditor-2' ) )
			.then( function(editor){
				console.log(editor);
			} )
			.catch(function(error) {
				//console.error( error );
			} );

		ClassicEditor
			.create( document.querySelector( '#kt-ckeditor-3' ) )
			.then( function(editor){
				console.log(editor);
			} )
			.catch(function(error) {
				//console.error( error );
			} );

		ClassicEditor
			.create( document.querySelector( '#kt-ckeditor-4' ) )
			.then( function(editor){
				console.log(editor);
			} )
			.catch(function(error) {
				//console.error( error );
			} );

		ClassicEditor
			.create( document.querySelector( '#kt-ckeditor-5' ) )
			.then( function(newEditor){
				editor = newEditor;
			} )
			.catch(function(error) {
				//console.error( error );
			} );
    }

    return {
        // public functions
        init: function() {
            demos(); 
        }
    };
}();

// Initialization
jQuery(document).ready(function() {
    KTCkeditor.init();
});