$('document').ready(function() {
    $.ajax('qa.php', {
        success: function(res) {
            page_data.surveys = res;
            renderSurveys();
        }
    });
});

var renderSurveys = function() {
    var surveyList = $.map(page_data.surveys, function(val, key) {
        return {
            id: key,
            label: key
        }
    });

    $HB('SurveyList', {
        surveyModels: surveyList
    }, '#survey_list');

    $('.clear_passwords').on('click', function(evt) {
        $('.my_survey').empty();
        $('.password_controls').hide();
        $('.password_controls .length-selection-container').empty();
        evt.stopPropagation();
        evt.preventDefault();
        return false;
    });

    $('.icons_toggle').on('click', function(evt) {
        var pwList = $('.password_list');
        if (pwList.hasClass('with_icons')) {
            pwList.removeClass('with_icons');
        } else {
            pwList.addClass('with_icons');
        }

        evt.stopPropagation();
        evt.preventDefault();
        return false;
    });

    $('.survey-list a').on('click', function(evt) {
        startSurvey('.my_survey', $(evt.currentTarget).attr('id'));
    });
};

var startSurvey = function(divSelector, surveyName) {
    $('.password_controls').hide();

    var surveyQuestions = page_data.surveys[surveyName];
    var questionsHtml = [];
    for (var i = 0; i < surveyQuestions.length; i++) {
        var question = surveyQuestions[i];
        var questionId = 'question_' + i; // id for the dom, so we easily read the answers back later
        var answerMarkup = '';
        switch(question.type) {
            case 'list':
                answerMarkup = $HB('ListAnswer', {
                    options: question.a
                });
                break;
            case 'checks':
                answerMarkup = $HB('ChecksAnswer', {
                    options: question.a
                });
                break;
            case 'text':
                answerMarkup = $HB('TextAnswer');
                break;
            case 'img':
                answerMarkup = $HB('ImageAnswer', {
                    options: question.a
                });
                break;
            default:
                console.log("unknown survey type");
        }
        questionsHtml.push($HB('Question', {
            answer: answerMarkup,
            id: questionId,
            questionText: question.q
        }));
    }

    $HB('Survey', {
        questions: questionsHtml
    }, divSelector);

    $(document).scrollTop(0);

    $('input[type=submit]').on('click', function(evt) {
        onSubmit(surveyName);
        evt.stopPropagation();
        evt.preventDefault();
        return false;
    });
};

var onSubmit = function(surveyName) {
    var surveyQuestions = page_data.surveys[surveyName];
    var answers = [];
    for (var i = 0; i < surveyQuestions.length; i++) {
        var question = surveyQuestions[i];
        var $answerDiv = $('#question_' + i + ' .answer');
        var answer = null;

        switch(question.type) {
            case 'list':
                answer = $answerDiv.find('select').val();
                break;
            case 'img':
                answer = getCheckboxesValue($answerDiv);
                break;
            case 'checks':
                answer = getCheckboxesValue($answerDiv);
                break;
            case 'text':
                var text = $.trim($answerDiv.find('textarea').val());
                answer = text ? [text] : null;
                break;
        }

        if (answer == null) {
            answer = [question.d];
        }

        answers = answers.concat(answer);
    }

    var inputStr = answers.join(page_data.submit.separator);
    this.passwordLists = createPasswordLists(inputStr);
    renderPasswordLists();
};

var renderPasswordLists = function(pwLength) {
    var selectedLengthVal = !!pwLength ? pwLength : this.passwordLists.lengths["default"];

    var pwWith = this.passwordLists['with'];
    var pwWithout = this.passwordLists.without;
    var pwSeed = this.passwordLists.seed;
    var iter = Math.max(pwWith.length, pwWithout.length);

    var pairs = [];
    for (var i = 0; i < iter; i++) {
        var iconUrl = 'images/icons/' + pad((i % 117) + 1, 4) + '.png';
        pairs.push({
            num: i+1,
            pwWith:    i < pwWith.length    ? pwWith[i].substring(0, selectedLengthVal)    : '',
            pwWithout: i < pwWithout.length ? pwWithout[i].substring(0, selectedLengthVal) : '',
            pwSeed: pwSeed[i],
            iconUrl: iconUrl
        });
    }

    $HB('PasswordList', { pairs: pairs }, '.my_survey');
    var lengthOptions = [];
    var lengthValues = this.passwordLists.lengths.values;

    for (var i = 0; i < lengthValues.length; i++) {
        lengthOptions.push({
            val: lengthValues[i],
            isSelected: lengthValues[i] == selectedLengthVal
        })
    }
    $HB('LengthSelection', {lengthOptions: lengthOptions}, '.length-selection-container');

    $('#password-length').on('change', function(evt) {
        var val = $(evt.currentTarget).val();
        renderPasswordLists(val)
    });

    $('.password_controls').show();
    $(document).scrollTop(0);
};

var getCheckboxesValue = function($answerDiv) {
    var checkedCheckboxes =  $answerDiv.find('input:checkbox:checked');
    var answer;

    if (!checkedCheckboxes.length) {
        answer = null;
    } else {
        answer = [];
        for (var i = 0; i < checkedCheckboxes.length; i++) {
            answer.push($(checkedCheckboxes[i]).val());
        }
    }

    return answer;
};
