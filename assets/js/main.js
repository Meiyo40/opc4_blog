tinymce.init({
    selector: 'textarea',
    height: 500,
    menubar: false,
    plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table paste code help wordcount'
    ],
    language: 'fr_FR',
    forced_root_block: "",
    entity_encoding: 'raw',
    encoding: "UTF-8",
    toolbar: 'insertfile undo redo | styleselect | bold italic | link image',
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tiny.cloud/css/codepen.min.css'
    ]
});