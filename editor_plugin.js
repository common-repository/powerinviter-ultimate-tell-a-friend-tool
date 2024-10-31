(function() {
	tinymce.PluginManager.requireLangPack('PowerInviter');

	tinymce.create('tinymce.plugins.PowerInviter', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceInvite');
			ed.addCommand('mceInvite', function() {
				var content = tinyMCE.activeEditor.selection.getContent({format : 'raw'});
				var newcontent = '[powerinviter]';
				
				tinyMCE.activeEditor.selection.setContent(newcontent);
			});
			
			ed.addButton('powerinviter', {
				title : 'PowerInviter: Insert the "Tell a friend" button shortcode',
				cmd : 'mceInvite',
				image : url + '/inviteeditor.png'
			});
			
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'PowerInviter editor plugin',
				author : 'PowerInviter.com',
				authorurl : 'http://PowerInviter.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/powerinviter/',
				version : "1.0"
			};
		}
	});

	tinymce.PluginManager.add('PowerInviter', tinymce.plugins.PowerInviter);
})();