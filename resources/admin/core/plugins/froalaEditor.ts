export const froalaEditorMathConfig = (imgPath?: string) => ({
  key: 'cJC7bE6C2F2C2C1D2yQNDMIJg1IQNSEa1EUAi1XVFQd1EaG3C2A5D5C4D3C2D4G2H1==',
  imageEditButtons: ['wirisEditor', 'imageDisplay', 'imageAlign', 'imageInfo', 'imageRemove'],
  toolbarButtons: [
    'undo',
    'redo',
    'superscript',
    '|',
    'insertTable',
    '|',
    'wirisEditor',
    'wirisChemistry',
    '|',
    'insertImage',
    'html',
  ],
  editor_math: false,
  imageUploadURL: imgPath ? route('app.upload.image', { path: imgPath }) : route('app.upload.image'),
  imageUploadMethod: 'POST',
  htmlAllowedTags: ['.*'],
  htmlAllowedAttrs: ['.*'],
  htmlAllowedEmptyTags: ['mprescripts'],
  imageResize: true,
  events: {
    initialized() {
      const contentRendered = WirisPlugin.Parser.initParse((this as any).html.get(true));
      (this as any).html.set(contentRendered);
    },
    'image.uploaded'(response) {
      const data = JSON.parse(response).data;
      const editor = this as any;
      editor.image.insert(data.url, false, null, editor.image.get(), response);
      return false;
    },
  },
  attribution: false,
  enter: window.FroalaEditor.ENTER_BR,
  tableCellStyles: {
    'border-top-none': 'border-top-none',
    'border-bottom-none': 'border-bottom-none',
    'border-left-none': 'border-left-none',
    'border-right-none': 'border-right-none',
    'border-black': 'border-black',
    'border-red': 'border-red',
    'border-blue': 'border-blue',
    'border-yellow': 'border-yellow',
  },
});
