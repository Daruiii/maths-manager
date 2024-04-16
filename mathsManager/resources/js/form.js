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
    var statement = CodeMirror.fromTextArea(document.getElementById('statement'), {
        lineNumbers: true,
        mode: 'stex',
        theme: 'ttcn',
        lineWrapping: true,
        viewportMargin: Infinity,
        extraKeys: {
            'Ctrl-Space': 'autocomplete',
        }
    });

    var solution = CodeMirror.fromTextArea(document.getElementById('solution'), {
        lineNumbers: true,
        mode: 'stex',
        theme: 'ttcn',
        lineWrapping: true,
        viewportMargin: Infinity,
        extraKeys: {
            'Ctrl-Space': 'autocomplete',
            'Ctrl-Enter': function(cm) {
                document.getElementById('exerciseForm').submit();
            }
        }
    });

    var clue = CodeMirror.fromTextArea(document.getElementById('clue'), {
        lineNumbers: true,
        mode: 'stex',
        theme: 'ttcn',
        lineWrapping: true,
        viewportMargin: Infinity,
        extraKeys: {
            'Ctrl-Space': 'autocomplete',
            'Ctrl-Enter': function(cm) {
                document.getElementById('exerciseForm').submit();
            }
        }
    });
}