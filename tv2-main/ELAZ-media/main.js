document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementById('addPlayer');
    const playersContainer = document.getElementById('playersContainer');
    const playersInfo = document.getElementById('playersInfo');
    let currentPlayer = 1;

    button.addEventListener('click', function() {
        if (currentPlayer <= 3) {
            const player = document.createElement('div');
            player.id = `player`+{currentPlayer};
            let valu = currentPlayer.toString()
            player.innerHTML = `
                <h3>Плеер ${currentPlayer}</h3>
                <video width="300" height="200" controls>
                <source src="https://test.plrjs.com/sample.mp4" type="video/mp4">
                </video>
                <br/>
                <button id="addFileButton">Добавить файл</button>
                <input id="fileInput" type="file">
                <div id="folder${currentPlayer}"></div>
                `;
            playersContainer.appendChild(player);

            const script = document.createElement('script');
            script.src = `Players/player`+{currentPlayer}+`.js`;
            script.type = 'text/javascript';
            document.head.appendChild(script);

            currentPlayer++;

        } else {
            alert('Добавлено максимальное количество видеопроигрывателей');
        }
        });
        playersContainer.addEventListener('click', function(event) {
            if (event.target.classList.contains('renameButton')) {
                const player = event.target.parentNode;
                const newName = player.querySelector('.renameInput').value;
                player.querySelector('h3').innerText = `$\{newName}`;
            }

            if (event.target.classList.contains('addFileButton')) {
                const player = event.target.parentNode;
                const fileInput = player.querySelector(`#fileInput`);
                const file = fileInput.files[0];
                const reader = new FileReader();
                reader.onload = function(event) {
                    player.querySelector('video').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }

    });
});
