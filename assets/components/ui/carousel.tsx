'use client'

import * as React from 'react'
import { ChevronLeft, ChevronRight, X } from 'lucide-react'
import { Button } from "@/components/ui/button"
import { Dialog, DialogContent, DialogClose } from "@/components/ui/dialog"
import { ReactNode, useEffect, useState } from 'react'

interface CarouselItem {
  id: number
  content: ReactNode
}

interface CarouselProps {
  items?: CarouselItem[]
  autoSlideInterval?: number
}

export default function Carousel({ items = [], autoSlideInterval = 3000 }: CarouselProps) {
  const [currentIndex, setCurrentIndex] = useState(0)
  const [isEnlarged, setIsEnlarged] = useState(false)
  const [enlargedIndex, setEnlargedIndex] = useState(0)

  const nextSlide = () => {
    if (items.length > 0) {
      setCurrentIndex((prevIndex) => (prevIndex + 1) % items.length)
    }
  }

  const prevSlide = () => {
    if (items.length > 0) {
      setCurrentIndex((prevIndex) => (prevIndex - 1 + items.length) % items.length)
    }
  }

  const handleImageClick = (index: number) => {
    setEnlargedIndex(index)
    setIsEnlarged(true)
  }

  useEffect(() => {
    if (autoSlideInterval > 0 && !isEnlarged && items.length > 0) {
      const timer = setInterval(() => {
        nextSlide()
      }, autoSlideInterval)

      return () => clearInterval(timer)
    }
  }, [currentIndex, autoSlideInterval, isEnlarged, items.length])

  if (!items || items.length === 0) {
    return <div className="p-4 text-center">No items to display</div>
  }

  return (
    <div className="relative max-w-md mx-auto w-60">
      <div className="overflow-hidden bg-white border-2 rounded-lg shadow-md border-border dark:border-darkBorder dark:shadow-dark dark:bg-gray-800">
        <div
          className="flex transition-transform duration-300 ease-in-out"
          style={{ transform: `translateX(-${currentIndex * 100}%)` }}
        >
          {items.map((item, index) => (
            <div key={item.id} className="flex-shrink-0 w-full">
              <div className="w-full cursor-pointer aspect-square" onClick={() => handleImageClick(index)}>
                {React.isValidElement(item.content) && React.cloneElement(item.content as React.ReactElement, {
                  className: "w-full h-full object-cover rounded-lg",
                })}
              </div>
            </div>
          ))}
        </div>
      </div>
      <Button
        variant="neutralStatic"
        size="icon"
        className="absolute top-0 bottom-0 my-auto left-2"
        onClick={prevSlide}
        aria-label="Previous"
      >
        <ChevronLeft className="w-4 h-4" />
      </Button>
      <Button
        variant="neutralStatic"
        size="icon"
        className="absolute top-0 bottom-0 my-auto right-2"
        onClick={nextSlide}
        aria-label="Next"
      >
        <ChevronRight className="w-4 h-4" />
      </Button>

      <Dialog open={isEnlarged} onOpenChange={setIsEnlarged}>
        <DialogContent className="max-w-[90vw] max-h-[90vh] p-0 overflow-hidden">
          <div className="relative w-full h-full">
            {items[enlargedIndex] && React.isValidElement(items[enlargedIndex].content) &&
              React.cloneElement(items[enlargedIndex].content as React.ReactElement, {
                className: "w-full h-full object-contain",
              })
            }
          </div>
        </DialogContent>
      </Dialog>
    </div>
  )
}

export { Carousel }