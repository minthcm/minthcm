<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calc DLNC</title>
    <script type='text/javascript' src='{sugar_getjspath file="include/LastNextContacts/entrypoints/CalculateDLNC.js"}' defer></script>
    {* <script type='text/javascript' src='include/LastNextContacts/entrypoints/CalculateDLNC.js' defer></script> *}
    {literal}
    <style type="text/css">
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-color: #fff;
            --bg-color2: #aaa;
            --text-color: #000;
            --text-color2: #aaa;
            --checkbox-color1: #fff;
            --checkbox-color2: #aaa;
            --shadow: 0 0 10px #000;
        }

        body {
            width: 100vw;
            height: 100vh;
            background: var(--bg-color);
            overflow: hidden;
            font-family: sans-serif;
            color: var(--text-color);
            position: relative;
        }

        .section {
            width: 100%;
            height: calc(100% - 40px);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-y: scroll;
        }

        @media only screen and (min-height: 500px) {
            .section {
                overflow: hidden;
            }
        }

        .footer {
            width: 100%;
            height: 40px;
            box-shadow: var(--shadow);
            background: var(--bg-color);
            color: #666;
            padding: 12px;
        }

        .form {
            width: 300px;
            margin: 50px;
            box-shadow: var(--shadow);
            border-radius: 5px;
            padding: 30px 20px;
            display: grid;
            grid-gap: 25px;
            grid-template-rows: repeat(3, 40px);
        }

        main {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        #alert {
            position: absolute;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            z-index: 2;
            background: rgba(0, 0, 0, 0.8);
            color:#fff;
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .checkbox {
            display: flex;
            justify-content: space-between;
            padding: 3px;
            align-items: center;
        }

        .checkbox--div {
            position: relative;
            width: 38px;
            height: 20px;
            border-radius: 9px;
            background: var(--checkbox-color1);
            cursor: pointer;
            transition: background-color .1s linear;
            border: 1px solid var(--bg-color2);
        }

        .checkbox--div::after {
            content: '';
            position: absolute;
            display: block;
            width: 14px;
            height: 14px;
            left: 2px;
            top: 2px;
            background-color: var(--checkbox-color2);
            border-radius: 50%;
            transition: transform .1s linear, background-color .1s linear;
        }

        .checkbox--input:checked~.checkbox--div::after {
            transform: translate(18px);
            background-color: var(--checkbox-color1);
        }

        .checkbox--input:checked~.checkbox--div {
            background-color: var(--bg-color2);
        }

        .checkbox--input {
            display: none;
        }

        #module_name {
            background: var(--bg-color);
            color: var(--text-color);
            border-radius: 5px;
            cursor: pointer;
        }

        #module_name>option {
            background: var(--bg-color);
            color: var(--text-color);
        }

        #calculate {
            border: 1px solid var(--text-color);
            background: var(--bg-color);
            color: var(--text-color);
            border-radius: 5px;
            cursor: pointer;
        }

        .progressbar {
            width: 100%;
            height: 5px;
            background: var(--bg-color2);
            display:none;
        }

        #progressbar {
            width: 0%;
            height: 5px;
            transition: width .2s linear;
            background: #0f0;
        }

        @keyframes progress_animation{
            0%{
                box-shadow:0 0 5px #ffa200;
            }
            50%{
                box-shadow:0 0 50px 2px #ffa200;
            }
            100%{
                box-shadow:0 0 5px #ffa200;        
            }
        }

        .progressbar_status{
            display:none;
            justify-content:space-between;
            font-size:12px;
            color:var(--text-color2);
        }
    </style>
    {/literal}
</head>

<body>
    <section id="alert">{$lang.LBL_CALCULATE_DLNC_ALERT}</section>
    <main>
        <div class="section">
            <div class="form">
                <select id="module_name">
                    <option value="candidates">{$lang.LBL_CALCULATE_DLNC_CANDIDATES}</option>
                </select>
                <label class="checkbox">{$lang.LBL_CALCULATE_DLNC_ONLY_NULL_RECORDS}
                    <input type="checkbox" id="only_null_records" class="checkbox--input">
                    <div class="checkbox--div"></div>
                </label>
                <button id="calculate">{$lang.LBL_CALCULATE_DLNC_BUTTON_CALCULATE}</button>
                <div class="progressbar">
                    <div id="progressbar"></div>
                </div>
                <div class="progressbar_status">
                    <p id="progressbar_status--1">0/0</p>
                    <p id="progressbar_status--2">?s</p>
                </div>
            </div>
        </div>
        <div class="footer">
            <p>{$lang.LBL_CALCULATE_DLNC_FOOTER}</p>
        </div>
    </main>
</body>

</html>