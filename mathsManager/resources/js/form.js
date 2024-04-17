export function loaderForm() {
    var form = document.querySelector("#exerciseForm");
    if (!form) {
        return;
    }
    form.addEventListener("submit", function() {
        document.getElementById("loadingPopup").style.display = "block";
    });
}

export function renderCodeMirror() {
    var statementID = document.getElementById('statement');
    if (statementID) {
        var statement = CodeMirror.fromTextArea(statementID, {
            lineNumbers: true,
            mode: 'stex',
            theme: 'ttcn',
            lineWrapping: true,
            viewportMargin: Infinity,
            extraKeys: {
                'Ctrl-Space': 'autocomplete',
            }
        });
    }
    var solutionID = document.getElementById('solution');
    if (solutionID) {
        var solution = CodeMirror.fromTextArea(solutionID, {
            lineNumbers: true,
            mode: 'stex',
            theme: 'ttcn',
            lineWrapping: true,
            viewportMargin: Infinity,
            extraKeys: {
                'Ctrl-Space': 'autocomplete',
            }
        });
    }

    var clueID = document.getElementById('clue');
    if (clueID) {
        var clue = CodeMirror.fromTextArea(clueID, {
            lineNumbers: true,
            mode: 'stex',
            theme: 'ttcn',
            lineWrapping: true,
            viewportMargin: Infinity,
            extraKeys: {
                'Ctrl-Space': 'autocomplete',
            }
        });
    }
}