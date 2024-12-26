<div>
    <div class="slideshow-container">
        @foreach ($imagePaths as $imagePath)
            <div class="mySlides fade">
                <img src="{{ asset($imagePath) }}" style="width:100%">
            </div>
        @endforeach
    </div>
</div>
<script>
    let slideIndex = 0;
    showSlides();

    function showSlides() {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        for (i = 0; i < slides.length; i++) {
            slides[i].style.opacity = "0"; // Use opacity instead of display
        }
        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1;
        }
        slides[slideIndex - 1].style.opacity = "1"; // Use opacity instead of display
        setTimeout(showSlides, 4500); // Change image every 4.5 seconds
    }
</script>
