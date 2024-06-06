import React, { useState, useEffect } from 'react';

const slides = [
    {
        src: "/assets/img/news-1.jpg",
        caption: "APPLICATION DE GESTION DES EMPLOYES.",
        subCaption: "Kofi Annan"
    },
    {
        src: "/assets/img/Employe.webp",
        caption: "LE SUCCES VIENT A CEUX QUI, TRAVAILLENT DUR ET RESTENT CONCENTRES.",
        subCaption: "Colin Powell"
    },
    {
        src: "/assets/img/news-5.jpg",
        caption: "MODERNISATIONS NOTRE PAYS EN CREANT DES ETREPRISES.",
        subCaption: "Victor Hugo"
    }
];

const Carousel = () => {
    const [currentIndex, setCurrentIndex] = useState(0);

    useEffect(() => {
        const interval = setInterval(() => {
            setCurrentIndex((prevIndex) => (prevIndex + 1) % slides.length);
        }, 3000); // Change slide every 3 seconds
        return () => clearInterval(interval);
    }, []);

    return (
        <main className="relative w-full h-screen">
            {slides.map((slide, index) => (
                <div
                    key={index}
                    className={`absolute inset-0 transition-opacity duration-1000 ${index === currentIndex ? 'opacity-100' : 'opacity-0'}`}
                >
                    <img
                        className="w-full h-full object-cover"
                        src={slide.src}
                        alt={slide.caption}
                    />
                    <div className="absolute inset-0 bg-black bg-opacity-50 flex flex-col items-center justify-center text-center text-white p-4">
                        <h5 className="text-2xl md:text-4xl font-bold">{slide.caption}</h5>
                        <p className="text-lg md:text-2xl mt-2">{slide.subCaption}</p>
                    </div>
                </div>
            ))}
        </main>
    );
};

export default Carousel;
