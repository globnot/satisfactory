'use client'

import * as React from 'react'
import { ChevronLeft, ChevronRight } from 'lucide-react'
import { Button } from "@/components/ui/button"
import { ReactNode, useEffect, useState } from 'react'

interface CarouselItem {
  id: number
  content: ReactNode
}

interface CarouselProps {
  items: CarouselItem[]
  autoSlideInterval?: number // Optionnel : intervalle pour le diaporama automatique
}

const Carousel: React.FC<CarouselProps> = ({ items, autoSlideInterval = 3000 }) => {
  const [currentIndex, setCurrentIndex] = useState(0)

  const nextSlide = () => {
    setCurrentIndex((prevIndex) => (prevIndex + 1) % items.length)
  }

  const prevSlide = () => {
    setCurrentIndex((prevIndex) => (prevIndex - 1 + items.length) % items.length)
  }

  useEffect(() => {
    if (autoSlideInterval > 0) {
      const timer = setInterval(() => {
        nextSlide()
      }, autoSlideInterval)

      return () => clearInterval(timer)
    }
  }, [currentIndex, autoSlideInterval])

  return (
    <div className="relative max-w-md mx-auto w-60">
      <div className="overflow-hidden bg-white border-2 rounded-base border-border dark:border-darkBorder shadow-light dark:shadow-dark dark:bg-secondaryBlack">
        <div
          className="flex transition-transform duration-300 ease-in-out"
          style={{ transform: `translateX(-${currentIndex * 100}%)` }}
        >
          {items.map((item) => (
            <div key={item.id} className="flex-shrink-0 w-full">
              <div className="">
                {/* Conteneur carré */}
                <div className="w-full aspect-square">
                  {React.isValidElement(item.content) && React.cloneElement(item.content as React.ReactElement, {
                    className: "w-full h-full object-cover rounded",
                  })}
                  {/* Si content n'est pas une image, ajustez selon vos besoins */}
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
      {/* Bouton Précédent */}
      <Button
        variant="neutralStatic"
        size="icon"
        className="absolute top-0 bottom-0 my-auto left-2"
        onClick={prevSlide}
        aria-label="Précédent"
      >
        <ChevronLeft className="w-4 h-4" />
      </Button>
      {/* Bouton Suivant */}
      <Button
        variant="neutralStatic"
        size="icon"
        className="absolute top-0 bottom-0 my-auto right-2"
        onClick={nextSlide}
        aria-label="Suivant"
      >
        <ChevronRight className="w-4 h-4" />
      </Button>
    </div>
  )
}

export { Carousel }
