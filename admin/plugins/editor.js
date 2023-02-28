import Vue from 'vue'
// Require Froala Editor js file.
import 'froala-editor/js/froala_editor.pkgd.min.js'
import 'froala-editor/js/plugins/align.min.js'
import 'froala-editor/js/plugins/char_counter.min.js'
import 'froala-editor/js/plugins/code_beautifier.min.js'
import 'froala-editor/js/plugins/code_view.min.js'
import 'froala-editor/js/plugins/colors.min.js'
import 'froala-editor/js/plugins/draggable.min.js'
import 'froala-editor/js/plugins/entities.min.js'
import 'froala-editor/js/plugins/font_family.min.js'
import 'froala-editor/js/plugins/font_size.min.js'
import 'froala-editor/js/plugins/fullscreen.min.js'
import 'froala-editor/js/plugins/draggable.min.js'
import 'froala-editor/js/plugins/image.min.js'
import 'froala-editor/js/plugins/link.min.js'
import 'froala-editor/js/plugins/lists.min.js'
import 'froala-editor/js/plugins/line_breaker.min.js'
import 'froala-editor/js/plugins/line_height.min.js'
import 'froala-editor/js/plugins/table.min.js'
import 'froala-editor/js/plugins/paragraph_format.min.js'
import 'froala-editor/js/plugins/paragraph_style.min.js'
import 'froala-editor/js/plugins/special_characters.min.js'
import 'froala-editor/js/plugins/quote.min.js'
import 'froala-editor/js/plugins/word_paste.min.js'
// Require Froala Editor css files.
import 'froala-editor/css/froala_editor.pkgd.min.css'
import 'froala-editor/css/froala_style.min.css'
  
// Import and use Vue Froala lib.
import VueFroala from 'vue-froala-wysiwyg'
Vue.use(VueFroala)

export default function ({ $axios }) {
    Vue.prototype.$defaultOpt = {
        placeholderText: 'Edit Your Content Here!',
        imageUpload: true,
        events: {
            'image.beforeUpload': function (images) {
              // Do something here.
              // this is the editor instance.
              const data = new FormData();
              data.append('file', images[0]);
              $axios.post('/api/upload/content', data).then(response => {
                this.image.insert(response.data.link, null, null, this.image.get());
              }).catch(err => {
                console.log(err);
              });
              return false
            }
        }        
    };
}
