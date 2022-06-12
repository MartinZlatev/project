(async function getMyImages(){
    const allImageList= document.getElementById("gallery");
    const fetched = await fetch("../../server/api/list_images/load-my-images.php", {
        headers: {
            "Content-Type": "application/json",
        },
    })
    const response = await fetched.json();
    for(image of response["data"]){
        const img = document.createElement('img');
        img.src= `../../server/images/${image["path"]}`
        allImageList.appendChild(img);  
    }
})()
