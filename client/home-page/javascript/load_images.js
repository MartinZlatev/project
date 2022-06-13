async function loadImages(){
    const responseDiv = document.getElementById('export-result');
    responseDiv.innerHTML="";
    const major = document.getElementById('export-major').value
    const course = document.getElementById('export-course').value

    const fetched = await fetch(`../../server/api/album/export-album.php?course=${course}&major=${major}`);
    const response= await fetched.json();
    console.log(response["data"]);
    const data = await response["data"];
    
    for( const image of data){
        const div= document.createElement('div');
        div.classList.add('my-image');
        const img = document.createElement('img');
        img.src= `../../server/images/${image["path"]}`
        div.appendChild(img);
        responseDiv.appendChild(div);  
    }
    
}