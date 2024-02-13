function toggleAccordion(element) {
    const accordionContent = element.nextElementSibling;
    element.classList.toggle('expanded');
    accordionContent.style.display = accordionContent.style.display === 'none' ? 'block' : 'none';
}
