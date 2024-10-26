// components/SatisfactoryBp.tsx

import * as React from "react";
import { Carousel } from "@/components/ui/carousel";
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip'
import { CalendarClock, Download, Hammer, Heart, RefreshCcw, User } from "lucide-react";

interface Block {
    id: number;
    title: string;
    description: string;
    author: string;
    createdAt: string;
    updatedAt: string;
    downloadCount: number;
    thankCount: number;
    images: string[];
    sbp: string[];
    sbpcfg: string[];
}

interface SatisfactoryBpProps {
    blocks: Block[];
}

const SatisfactoryBp: React.FC<SatisfactoryBpProps> = ({ blocks }) => {
    // Gestion de l'état local des blocs
    const [blocksState, setBlocksState] = React.useState<Block[]>(blocks);

    // Fonction pour gérer le clic sur "Remercier"
    const handleThank = async (id: number) => {
        try {
            const response = await fetch(`/satisfactory/blueprint/${id}/thank`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // Indique une requête AJAX
                },
            });

            const data = await response.json();

            if (!response.ok) {
                // Afficher une notification ou un message d'erreur
                alert(data.error || 'Erreur lors de l\'envoi du remerciement');
                return;
            }

            // Mettre à jour le thankCount dans l'état local
            setBlocksState(prevBlocks => prevBlocks.map(block => {
                if (block.id === id) {
                    return { ...block, thankCount: data.thankCount };
                }
                return block;
            }));
        } catch (error) {
            console.error(error);
            // Afficher une notification d'erreur à l'utilisateur
            alert('Erreur réseau, veuillez réessayer plus tard.');
        }
    };

    return (
        <div className="flex flex-wrap justify-center gap-12">
            {blocksState.map((block) => (
                <div key={block.id} className="w-[350px]">
                    <Card className="flex flex-col h-full">

                        <CardHeader>

                            {/* Title */}
                            <CardTitle className="uppercase">
                                <div className="flex items-center justify-start">
                                    <Hammer size={28} className="mr-2 text-secondary" />{block.title}
                                </div>
                            </CardTitle>

                            {/* Description */}
                            <CardDescription>
                                <div className="italic">
                                    "{block.description}"
                                </div>
                            </CardDescription>

                            {/* Dates */}
                            <div className="flex items-start justify-start pt-4">
                                <CalendarClock size={16} className="mr-2 text-secondary" />
                                <span className="text-xs">{block.createdAt}</span>
                            </div>
                            <div className="flex items-start justify-start">
                                <RefreshCcw size={16} className="mr-2 text-secondary" />
                                <span className="text-xs">{block.updatedAt}</span>
                            </div>


                            <div className="flex items-center justify-between pt-4">

                                {/* Author */}
                                <div className="flex items-center justify-start">
                                    <User size={20} className="mr-2 text-secondary" />
                                    <span className="font-bold">{block.author}</span>
                                </div>

                                {/* Download count */}
                                <div className="flex items-center justify-end">
                                    <Download size={20} className="mr-2 text-secondary" />
                                    <span className="font-bold">{block.downloadCount}</span>
                                    <span className="text-xs uppercase ms-2">Downloads</span>
                                </div>
                            </div>

                        </CardHeader>

                        {/* Carousel */}
                        <CardContent className="flex-grow">
                            <Carousel
                                items={block.images.map((src, imgIndex) => ({
                                    id: imgIndex + 1,
                                    content: (
                                        <img
                                            src={src}
                                            alt={`${block.title} ${imgIndex + 1}`}
                                            className="carousel-image"
                                        />
                                    )
                                }))}
                                autoSlideInterval={0}
                            />
                        </CardContent>

                        {/* Footer */}
                        <CardFooter className="flex flex-wrap justify-center gap-4 mt-auto">

                            {/* Download sbp button */}
                            <a href={`/satisfactory/blueprint/${block.id}/download/sbp`}>
                                <Button variant="default">Download .sbp (indispensable)</Button>
                            </a>

                            {/* Download sbpcfg button */}
                            <a href={`/satisfactory/blueprint/${block.id}/download/sbpcfg`}>
                                <TooltipProvider>
                                    <Tooltip>
                                        <TooltipTrigger asChild>
                                            <Button variant="neutral" size="sm" className="text-xs">
                                                Download .sbpcfg (optionnel)
                                            </Button>

                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>Uniquement si vous souhaitez conserver la description, l'icône et la couleur</p>
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                            </a>

                            {/* Thank button */}
                            <Button
                                variant="secondary"
                                onClick={() => handleThank(block.id)}
                            >
                                Remercier
                            </Button>
                            <div className="flex items-center justify-end">
                                <span className="text-xs uppercase"></span>
                                <span className="ml-2 font-bold">{block.thankCount}</span>
                                <Heart size={20} className=" text-secondary ms-2" strokeWidth={3} />
                            </div>
                        </CardFooter>

                    </Card>
                </div>
            ))}
        </div>
    )
}

export default SatisfactoryBp;
