let allImages = document.getElementsByClassName('img-thumbnail')
let photosToRemove = toArray(document.getElementById('photos_to_remove').value)

for(let i = 0; i < allImages.length; i++){

    allImages[i].addEventListener('click', (e)=> {

        let selectedPhoto = e.currentTarget;
        let srcPathArray = selectedPhoto.fullSrc.split('/');
        let src = srcPathArray[srcPathArray-length - 1]

        if (!photosToRemove.includes(src)){
            selectedPhoto.classList.toggle('opacity');
            photosToRemove.push(src)
        }
        else{

            selectedPhoto.classList.toggle('opacity');
            let photosToRemove = photosToRemove.filter(item => item != src)
 
        }

        document.getElementById('photos_to_remove').value = photosToRemove
    })
}