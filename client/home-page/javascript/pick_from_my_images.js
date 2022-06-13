(async function getMyImages() {
    const pickAllImageList = document.getElementById("my-gallery");

    const fetched = await fetch("../../server/api/list_images/load-my-images.php", {
        headers: {
            "Content-Type": "application/json",
        },
    })
    const response = await fetched.json();
    for (image of response["data"]) {
        (function (image) {
            console.log('here');
            const div= document.createElement('div');
            div.classList.add('pick-my-image');
            const img = document.createElement('img');
            img.src = `../../server/images/${image["path"]}`
            img.addEventListener("click", () => setPicked(image["path"]));
            div.appendChild(img);
            pickAllImageList.appendChild(div);
        })(image);
    }
})()
async function setPicked(path) {
    console.log('here');
    const messageDiv = document.getElementById("pick-image-message");
    messageDiv.innerHTML = ""
    const fetched = await fetch(`../../server/api/pick_image/pick-my-image.php?path=${[path]}`)
    const response = await fetched.json();
    if (response["status"] === "SUCCESS") {
        messageDiv.innerHTML = "🎉✨Успешно избрахте снимката!🎉✨";
    } else {
        messageDiv.innerHTML = "Грешка";
        console.log('грешката брат');
    }
}