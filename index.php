<?php
	include_once('common.php');
	//redirectToHttps();
?>
<!--
// <div>
// <script language="JavaScript" src="https://secure.comodo.net/trustlogo/javascript/trustlogo.js" type="text/javascript">
// </script>
// <!--
// TrustLogo Html Builder Code:
// Shows the logo at URL https://www.passwordcharmer.com/images/secure_site.gif
// Logo type is  ("SC")
// Floating on the Bottom Right
// //
// <a href="http://www.instantssl.com" id="comodoTL">SSL</a>
// <script type="text/javascript">TrustLogo("https://www.passwordcharmer.com/pix/secure_site.gif", "PAIR", "topright");</script>
// </div>
-->
<!DOCTYPE html>
<html>
<head>
	<style>
		#toast {
		visibility: hidden;
		min-width: 250px;
		margin-left: -125px;
		background-color: #333;
		color: #fff;
		text-align: center;
		border-radius: 2px;
		padding: 16px;
		position: fixed;
		z-index: 1;
		left: 50%;
		bottom: 30px;
		font-size: 17px;
		}

		#toast.show {
		visibility: visible;
		-webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
		animation: fadein 0.5s, fadeout 0.5s 2.5s;
		}

		@-webkit-keyframes fadein {
			from {bottom: 0; opacity: 0;}
			to {bottom: 30px; opacity: 1;}
		}

		@keyframes fadein {
			from {bottom: 0; opacity: 0;}
			to {bottom: 30px; opacity: 1;}
		}

		@-webkit-keyframes fadeout {
			from {bottom: 30px; opacity: 1;}
			to {bottom: 0; opacity: 0;}
		}

		@keyframes fadeout {
			from {bottom: 30px; opacity: 1;}
			to {bottom: 0; opacity: 0;}
		}
	</style>

    <script src="resources/ext/jquery-1.11.0.js" type="application/javascript"></script>
    <script src="resources/ext/handlebars-v1.3.0.js" type="application/javascript"></script>
    <script src="resources/ext/bootstrap/bootstrap.min.js" type="application/javascript"></script>
    <script src="resources/ext/sha1.js" type="application/javascript"></script>
    <script src="resources/pc.js" type="application/javascript"></script>

    <script>
        var page_data = {
            submit: {
                url: "<?php echo constant('SUBMIT_URL'); ?>",
                separator: "<?php echo constant('SUBMIT_SEP'); ?>",
                arg: "<?php echo constant('SUBMIT_ARG'); ?>"
            }
        }
    </script>

    <script src="resources/lib.js" type="application/javascript"></script>
    <script src="resources/surveys.js" type="application/javascript"></script>

    <link href="resources/ext/bootstrap/bootstrap.min.css" rel="stylesheet" />
    <link href="resources/ext/bootstrap/bootstrap-theme.min.css" rel="stylesheet" />
    <link href="resources/site.css" rel="stylesheet" />

    <title>Password Charmer</title>
    <script id="ListAnswer" type="text/x-handlebars-template">
        <select multiple>
            {{#each options}}
                <option value="{{this}}">{{this}}</option>
            {{/each}}
        </select>
    </script>

    <script id="ChecksAnswer" type="text/x-handlebars-template">
        {{#each options}}
            <div class="checkbox"><label><input type="checkbox" value="{{this}}">{{this}}</label></div>
        {{/each}}
    </script>

    <script id="TextAnswer" type="text/x-handlebars-template">
        <textarea autocomplete="off" class="form-control" rows="3"></textarea>
    </script>

    <script id="ImageAnswer" type="text/x-handlebars-template">
        {{#each options}}
          <div class="checkbox">
              <label><input type="checkbox" value="{{this}}" /><img src="{{this}}" /></label>
          </div>
        {{/each}}
    </script>

    <script id="Question" type="text/x-handlebars-template">
        <div class="question-answer" id="{{id}}">
            <h4><div class="questionText">{{questionText}}</div></h4>
            <div class="answer">{{{answer}}}</div>
        </div>
    </script>

    <script id="Survey" type ="text/x-handlebars-template">
        <form class="survey">
            {{#each questions}}
                {{{this}}}
            {{/each}}
            <input class="btn btn-default" type="submit" value="Go" />
        </form>
    </script>

    <script id="SurveyList" type="text/x-handlebars-template">
        <ul class="survey-list">
            {{#each surveyModels}}
                <li><a href="javascript:void 0" id="{{id}}">{{label}}</a></li>
            {{/each}}
        </ul>
    </script>

	<script src="Buffer.min.0.0.0.js" type="application/javascript"></script>
	<script src="buffer.min.6.0.3.js" type="application/javascript"></script>
	<script src="bitcore-lib.min.8.23.1.js" type="application/javascript"></script>
	<script src="bitcore-mnemonic.min.8.23.1.js" type="application/javascript"></script>
	<script id="copyDivToClipboardFunction">
		function copyDivToClipboard(inputElement, originalTextContent) {
			var range = document.createRange();
			range.selectNode(inputElement);
			window.getSelection().removeAllRanges(); // clear current selection
			window.getSelection().addRange(range); // to select text
			document.execCommand("copy");
			window.getSelection().removeAllRanges();// to deselect

			inputElement.innerText = "Copied"
			var toast = document.getElementById("toast");
			toast.className = "show";
			setTimeout(function(){
					   toast.className = toast.className.replace("show", "");
					   }, 1500);
			setTimeout(function(){
					   inputElement.innerText = originalTextContent;
					   }, 500);

		}
		function createWallet(inputEntropy) {
			var Buffer = require('Buffer');
			var bitcore = require('bitcore-lib');
			var Mnemonic = require('bitcore-mnemonic');
			var seedData = Buffer.from(inputEntropy, 'utf8');
			var code = new Mnemonic(seedData, Mnemonic.Words.ENGLISH);
			return code.toString();
		}
		
	</script>

    <script id="PasswordList" type="text/x-handlebars-template">
        <table class="table table-striped password_list">
            <tr><th></th><th>with</th><th>without</th><th>BIP39 Seed</th></tr>
            {{#each pairs}}
                <tr>
				<td class="pw_label"><span class="num">{{num}}</span><span class="pw_icon"><img src="{{iconUrl}}"/></span></td>
				<td><span id="{{num}}With" onclick="copyDivToClipboard(this, '{{pwWith}}')">{{pwWith}}</span></td>
				<td><span id="{{num}}Without" onclick="copyDivToClipboard(this, '{{pwWithout}}')">{{pwWithout}}</span></td>
				<td><span id="{{num}}Seed" onclick="copyDivToClipboard(this, '{{pwSeed}}')">{{pwSeed}}</span></td>
                </tr>
            {{/each}}
        </table>
    </script>

    <script id="LengthSelection" type="text/x-handlebars-template">
        <select class="form-control" id="password-length">
            {{#each lengthOptions}}
                <option value="{{val}}" {{#if isSelected}}selected="selected"{{/if}}>{{val}} characters</option>
            {{/each}}
        </select>
    </script>
</head>
<body>
<div class="container-fluid">
    <div class="col-md-2" id="survey_list">&nbsp;</div>
    <div class="col-md-8">
        <div class="my_survey">
			<table>
				<tr>
					<td>
						<img src="icon-320.png" alt="Password Charmer" >
					</td>
					<td>
						<h2>&bull; Answer Questions</h2>
						<h2>&bull; Get Passwords</h2>
						<h2>Same answers = Same passwords</h2>
					</td>
				</tr>
			</table>
		</div>
    </div>
    <div class="col-md-2">
        <div class="password_controls">
			<img src="icon-320.png" alt="Password Charmer" width="75%">
			<br>
            <button class="btn btn-default icons_toggle">Icons Toggle</button>
            <button class="btn btn-default clear_passwords">Clear</button>
			<br><br>
			Password Length:<br><div class="length-selection-container"></div>
			<br><br>
			<div>
			Please consider supporting<br>
			Password Charmer<br>
			with a small donation of Bitcoin<br>
			<img src="bitcoin.png" alt="bc1qhq40sdc3s7akfmcv4zjlhjludhfr7c5eyzegsx" title="bc1qhq40sdc3s7akfmcv4zjlhjludhfr7c5eyzegsx" width="75%">
			</div>
        </div>
    </div>
</div>
<div id="toast">Password Copied</div>
</body>
</html>
