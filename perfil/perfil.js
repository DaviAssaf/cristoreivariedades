document.getElementById('profile-pic-input').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('profile-pic').src = reader.result;
        }
        reader.readAsDataURL(file);
    } else {
        alert("Por favor, selecione um arquivo de imagem");
    }
});

function setAvatar(avatarSrc) {
    document.getElementById('profile-pic').src = avatarSrc;
}