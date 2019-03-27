tinymce.init({
    selector: '.full-editor',
    height: 500,
    theme: 'modern',
    plugins: 'table wordcount code',
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.sun3dcorporation.com/css/3dsun.css'],
    toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    style_formats: [
        { title: 'Title', inline: 'h1', classes: 't-g title' },
        { title: 'Subtitle', inline: 'h3', classes: 't-g subtitle' },
        { title: 'Paragraf', selector: 'p', inline: 'p', classes: 't-g paragraf' },
        { title: 'Bold text', inline: 'strong' },
        { title: 'Red text', inline: 'span', classes: 't-g' },
        
    ],
    formats: {
        alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'left' },
        aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'center' },
        alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'right' },
        alignfull: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'full' },
        bold: { inline: 'span', 'classes': 'bold' },
        italic: { inline: 'span', 'classes': 'italic' },
        underline: { inline: 'span', 'classes': 'underline', exact: true },
        strikethrough: { inline: 'del' },
        customformat: { inline: 'span', styles: { color: '#00ff00', fontSize: '20px' }, attributes: { title: 'My custom format' }, classes: 'example1' },
    }
});