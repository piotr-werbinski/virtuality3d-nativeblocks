var lastScrollTop = 0;

document.addEventListener('scroll', function() {
    var scrollTop = window.scrollY;
    var fadeOutElements = document.querySelectorAll('.container__model-header');
    
    fadeOutElements.forEach(function(element) {
        var elementOffsetTop = element.offsetTop;
        var elementHeight = element.offsetHeight;
        
        var distanceFromTop = Math.abs(scrollTop - elementOffsetTop);
        var maxDistance = window.innerHeight + elementHeight;
        var opacity = 1 - distanceFromTop / maxDistance;
        
        element.style.opacity = Math.max(0, Math.min(1, opacity));
    });
    
    lastScrollTop = scrollTop;
});