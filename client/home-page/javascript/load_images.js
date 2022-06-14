async function loadImages(){
    const responseDiv = document.getElementById('export-result');
    responseDiv.innerHTML="";
    const major = document.getElementById('export-major').value
    const course = document.getElementById('export-course').value

    const fetched = await fetch(`../../server/api/album/export-album.php?course=${course}&major=${major}`);
    const response= await fetched.json();
    const data = await response["data"];
    
    for( const image of data){
        const div= document.createElement('div');
        const div2=document.createElement('div');
        const div3=document.createElement('div');
        div.classList.add('album-image');
        div2.classList.add('user-album-info')
        div3.classList.add('user-album-image')
        const name = image["name"];
        div2.innerHTML=name;
        const img = document.createElement('img');
        img.src= `../../server/images/${image["path"]}`
        div3.appendChild(img);
        div.appendChild(div3);
        div.appendChild(div2);
        responseDiv.appendChild(div); 
         
    }
    
}