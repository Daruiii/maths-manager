export function loaderForm() {
    var form = document.querySelector("#dsForm");
    if (!form) {
        return;
    }
    form.addEventListener("submit", function(e){
        e.preventDefault(); //sert a empecher le formulaire de se soumettre tant qu'on a pas fini de traiter les données
        document.getElementById("loadingPopup").style.display = "block";
        // wait for 3 seconds
        setTimeout(function(){
            form.submit();
        }, 2000);
    });
}

export function renderCodeMirror() {

    var contentID = document.getElementById('content');
    if (contentID) {
        var content = CodeMirror.fromTextArea(contentID, {
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