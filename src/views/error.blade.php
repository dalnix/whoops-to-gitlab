<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Error</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 36px;
            padding: 20px;
        }

        .modal_overlay {
            background: rgba(0, 0, 0, 0.5);
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            align-items: center;
            display: none;
            justify-content: center;
            z-index: 10
        }

        .modal {
            display: none;
            width: 33vw;
            height: auto;
            padding: 20px;
            background: #fff;
            z-index: 15;
            color: #2a2a2a;
            max-height: 100vh;
            overflow-y: auto;
        }

        .modal-title {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .modal-title .close {
            float: right;
            cursor: pointer;
        }

        .modal-content {
            padding-top: 15px;
            padding-bottom: 15px;
        }

        .modal-footer {
            margin-top: 20px;
            padding-top: 10px;
            padding-bottom: 10px;
            border-top: 1px solid rgba(42, 42, 42, 0.4);
        }

        .bar {
            z-index: 5;
            position: fixed;
            top: 0;
            width: 100vw;
            height: 50px;
            box-sizing: border-box;
            background-color: #2a2a2a;
            color: #fff;
            -webkit-box-shadow: 0px 10px 5px 0px rgba(0, 0, 0, 0.12);
            -moz-box-shadow: 0px 10px 5px 0px rgba(0, 0, 0, 0.12);
            box-shadow: 0px 10px 5px 0px rgba(0, 0, 0, 0.12);
            display: table;

        }

        .button-wrapper {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }

        .btn {
            margin-right: 20px;
            z-index: 30;
            cursor: pointer;
        }

        .btn:hover {

        }

        table {
            border-spacing: 0; /* Removes the cell spacing via CSS */
            border-collapse: collapse; /* Optional - if you don't want to have double border where cells touch */

        }

        table thead tr th {
            padding-right: 15px;
            padding-bottom: 7px;
            text-align: left;
        }

        table thead tr th:last-child {
            padding-right: 0;
        }

        table tbody tr td {
            padding: 7px;
        }

        table tbody tr td:first-child {
            border-right: 1px solid rgba(42, 42, 42, 0.4);
        }

        table tbody tr td:last-child {
            padding-right: 0;
        }

        .form-group {
            display: block;
        }

        .form-group label {
            display: block;
        }

        .form-group .form-control {
            width: 100%;
        }
    </style>
</head>
<body>
<div class="modal_overlay">
    <div class="modal">
        <div class="modal-title">
            <span class="title">

            </span>
            <span class="close" onclick="closeModal()">X</span>
        </div>
        <div class="modal-content">

        </div>
        <div class="modal-footer">
            <button class="create-issue" onclick="postCreateIssue()">{{__('Create issue')}}</button>
        </div>
    </div>
</div>
<div class="bar">
    <div class="button-wrapper">
        <button class="btn" onclick="openShow()">{{__('Show error')}}</button>
        <button class="btn" onclick="openCreate()">{{__('Create issue on gitlab')}}</button>
    </div>
</div>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title">
            {{__('Whoops, looks like something went wrong.')}}
        </div>
    </div>
</div>

<script>
    var vars = {!! $vars !!}
            console.log(vars);
    var overlay = document.querySelector('.modal_overlay');
    var modal = document.querySelector('.modal');
    function closeModal() {
        clearModal();
        overlay.setAttribute('style', 'display: none;');
        modal.setAttribute('style', 'display: none;');
    }
    function clearModal() {
        modal.querySelector('.title').innerHTML = "";
        modal.querySelector('.modal-content').innerHTML = "";
    }
    function showModal(type) {
        overlay.setAttribute('style', 'display: flex;');
        modal.setAttribute('style', 'display: block;');
        if (type == 'show') {
            modal.querySelector('.modal-footer').setAttribute('style', 'display: none;')
        } else {
            modal.querySelector('.modal-footer').setAttribute('style', 'display: block;')
        }
    }
    function openShow() {
        modal.querySelector('.title').innerHTML = '{{__('Show error')}}';

        var html = '<table>'
                + '<thead><tr><th>{{__("Key")}}</th><th>{{__("Value")}}</tr></thead>'
                + '<tbody>';
        for (var prop in vars.e) {
            if (!vars.e.hasOwnProperty(prop) || prop == 'trace' || prop == 'traceAsString') continue;
            html += '<tr><td>' + prop + '</td><td>' + vars.e[prop] + '</td></tr>';
        }


        html += '</tbody></table>';

        modal.querySelector('.modal-content').innerHTML = html;

        showModal('show');

    }
    function openCreate() {
        modal.querySelector('.title').innerHTML = '{{__('Create issue on gitlab')}}';
        var html = '<form method="POST" action="{{route('whoopsToGitlab.post')}}">';
        html += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        html += '<input type="hidden" name="_data" value="{{ json_encode($vars) }}">';
        html += '<div class="form-group"><label for="title">{{__("Issue title")}}</label><input type="hidden" value="AUTO: ' + vars.e.message + '" name="auto_title"><input type="text" class="form-control form_title" value="" name="title"></div>';
        html += '<div class="form-group"><label for="issue">{{__("Issue description")}}</label><textarea class="form-control form_description" name="description">';
        html += '\n\n\n<h3>Error table</h3>\n';
        html += '| Key | Value |\n'
                + '| -------- | -------- |\n'
                + '| Message   | '+ vars.e.message +' |\n'
                + '| file  | '+ vars.e.file +'|\n'
                + '| line  | '+ vars.e.line +'|\n'
                + '| code  | '+ vars.e.code +'|\n'
                + '| prev  | '+ vars.e.prev +'|';
        html += '</textarea></div>';
        html += '</form>';
        modal.querySelector('.modal-content').innerHTML = html;
        showModal();
    }

    function postCreateIssue() {
        var title = document.querySelector('.form_title').value;
        var description = document.querySelector('.form_description').value;
        if (title !== '' && description !== '') {
            document.querySelector('form').submit();
        } else {
            alert('{{__('You must enter a title and a description')}}');
        }
    }
</script>
</body>
</html>