var _BBSR_edit_event = function(code_data,e) {
    console.log(code_data);
};

var _BBSR = {

	serialnumber:0, /* increment to create next serial no to rendered shortcode */ 
	selected_data:null,
	selected_serial:null,


	insertString: function(data){
		tinyMCE.execCommand('mceInsertContent', false, data);
	},

	tinnyClickGraber: function(e){
		try {
			var code_data = e.target.closest('.shortcode_render').getAttribute('data-repr');
			var code_index = e.target.closest('.shortcode_render').getAttribute('data-index');
			if(e.target.classList[0] == 'edit'){
				console.log('>> SHORTCODE edit');
				this.selected_serial = code_index;
				this.selected_data = this.parse_params(code_data);
				this.selected_data.serial = this.selected_serial;
				_BBSR_edit_event(this.selected_data,e);
			}
			if(e.target.classList[0] == 'delete'){
				e.target.closest('.shortcode_render').remove();
			}
			if(e.target.classList[0] == 'shortcode_render'){
				console.log('>> SHORTCODE: ',code_data);
			}
		}catch(err) {}
	},

	/* reEdit data format:
		{
			name: string
			params: {key:value,...}
			serial: number
		}
	*/
	reEditShortCode: function(data){
		var data = {
			name:"xxx",
			serial:1,
			params:{
				name:"ale jaja",
				value:"123"
			}
		}
		var mceDom = tinyMCE.activeEditor.dom;
		mceDom.setAttrib(mceDom.select('.shortcode_render[data-index='+data.serial+']'), 'data-repr', 'xxx');
	},

	parse_params: function(_p){
		var code = _p.split(' ')[0];
		_p = _p.replace(/"/g,'","');
		_p = _p.replace(/=",/g,'":"');
		_p = _p.replace(/"," /g,'","');
		_p = _p.replace(/""/g,'"');
		_p = _p.replace(code+' ','');
		_p = _p.slice(0,-2);
		_p = '{"'+_p+'}';
		_p = JSON.parse(_p);
		var out = {
			'name':code,
			'params': _p,
		}
		return out;
	}
}

/* run renderer after tinny load */
jQuery( document ).on( 'tinymce-editor-init', function( event, editor ) {
	//_BBSR.insertString('[test name="test"]');
	//_BBSR.reEditShortCode({});
});