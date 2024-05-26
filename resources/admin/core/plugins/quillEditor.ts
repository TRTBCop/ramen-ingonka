import ImageUploader from 'quill-image-uploader';
import htmlEditButton from 'quill-html-edit-button';

export const editorModules = [
  {
    name: 'imageUploader',
    module: ImageUploader,
    options: {
      upload: (file) => {
        return new Promise((resolve, reject) => {
          const formData = new FormData();
          formData.append('file', file);

          fetch('/api/upload/image', {
            method: 'POST',
            body: formData,
          })
            .then((response) => response.json())
            .then((result) => {
              resolve(result.data.url);
            })
            .catch((error) => {
              reject('Upload failed');
              console.error('Error:', error);
            });
        });
      },
    },
  },
  {
    name: 'htmlEditButton',
    module: htmlEditButton,
    options: {
      debug: true,
      msg: 'Edit the content in HTML format',
      okText: 'Ok',
      cancelText: 'Cancel',
      buttonHTML: '&lt;&gt;',
      buttonTitle: 'Show HTML source',
      syntax: false,
      prependSelector: 'div#myelement',
      editorModules: {},
    },
  },
];
