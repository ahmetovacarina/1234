document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementById('addPlayer');
    const playersContainer = document.getElementById('playersContainer');
    const playersInfo = document.getElementById('playersInfo');
    button.addEventListener('click', function() {
        const player = document.createElement('div');
        player.innerHTML = `
            <video width="300" height="200" controls>
                <source src="Players/player(i).mp4" type="video/mp4">
            </video>
        `;
        playersContainer.appendChild(player);
    });
    const renameButton = document.getElementById('renameButton');
    const renameInput = document.getElementById('renameInput');
    renameButton.addEventListener('click', function() {
        const player = document.getElementById(`player${currentPlayer}`);
        const newName = renameInput.value;
        player.innerHTML = `
            <h3>Плеер ${newName}</h3>
            <button id="renameButton">Переименовать</button>
            <input id="renameInput" type="text" value="${newName}">
        `;
    });
    const addFileButton = document.getElementById('addFileButton');
    const fileInput = document.getElementById('fileInput');
    addFileButton.addEventListener('click', function() {
        const file = fileInput.files[0];
        const reader = new FileReader();
        reader.onload = function(event) {
            const player = document.getElementById(`player${currentPlayer}`);
            player.innerHTML = `
                <video width="300" height="200" controls>
                    <source src="${event.target.result}" type="video/mp4">
                </video>
            `;
        };
        reader.readAsDataURL(file);
    });

});