tinymce.init({
    selector: '.mid-editor',
    height: 500,
    menubar: false,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor textcolor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code help wordcount'
    ],
    toolbar: 'insert | undo redo | formatselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    content_css: [
      '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
      '//www.tinymce.com/css/codepen.min.css'],
    style_formats: [
        { title: 'Title', inline: 'h1', classes: 't-g title' },
        { title: 'Subtitle', inline: 'h3', classes: 't-g subtitle' },
        { title: 'Paragraf', selector: 'p', inline: 'p', classes: 't-g paragraf' },
        { title: 'Bold text', inline: 'strong' },
        { title: 'Red text', inline: 'span', classes: 't-g' },
        
    ]
  });