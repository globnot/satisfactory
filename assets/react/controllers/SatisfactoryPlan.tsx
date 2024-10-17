import * as React from "react"
import { Carousel } from "@/components/ui/carousel"

interface SatisfactoryPlanProps {
    images: string[] // Définir le type des props
}

const SatisfactoryPlan: React.FC<SatisfactoryPlanProps> = ({ images }) => {
    // Créer les éléments du carousel à partir des URLs d'images
    const carouselItems = images.map((src, index) => ({
        id: index + 1,
        content: (
            <img
                src={src}
                alt={`Plans ${index + 1}`}
            />
        )
    }))

    return (
        <div>
            <Carousel items={carouselItems} autoSlideInterval={0} />
        </div>
    )
}

export default SatisfactoryPlan
