function myFunction(imgs) {
    // Get the expanded image
    let expandImg = document.getElementById("expandedImg");
    // Get the image text
    let imgText = document.getElementById("imgtext");
    // Use the same src in the expanded image as the image being clicked on from the grid
    expandImg.src = imgs.src;
  }
  
  let like = document.getElementById('like');

  like.addEventListener('click', (e) =>{

      e.currentTarget.classList.toggle('far')
      e.currentTarget.classList.toggle('fas')


  } )