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

            if (!response.ok) {
                throw new Error('Erreur lors de l\'envoi du remerciement');
            }

            const data = await response.json();

            // Mettre à jour le thankCount dans l'état local
            setBlocksState(prevBlocks => prevBlocks.map(block => {
                if (block.id === id) {
                    return { ...block, thankCount: data.thankCount };
                }
                return block;
            }));
        } catch (error) {
            console.error(error);
            // Optionnel : afficher une notification d'erreur à l'utilisateur
        }
    };

    return (
        <div className="flex flex-wrap justify-center gap-12">
            {blocksState.map((block) => (
                <div key={block.id} className="w-[350px]">
                    <Card className="flex flex-col h-full">
                        <CardHeader>
                            <CardTitle className="uppercase">
                                <div className="flex items-center justify-start">
                                    <Hammer size={28} className="mr-2 text-secondary" />{block.title}
                                </div>
                            </CardTitle>
                            <CardDescription>
                                {block.description}
                            </CardDescription>
                            <div className="pt-2">
                                <div className="flex items-start justify-start">
                                    <CalendarClock size={18} className="mr-2 text-secondary" />
                                    <span className="text-sm">{block.createdAt}</span>
                                </div>
                                <div className="flex items-start justify-start">
                                    <RefreshCcw size={18} className="mr-2 text-secondary" />
                                    <span className="text-sm">{block.updatedAt}</span>
                                </div>
                            </div>
                            <div className="flex items-center justify-start">
                                <User className="mr-2 text-secondary" />
                                <span className="font-bold">{block.author}</span>
                            </div>
                            <div className="flex items-center justify-end">
                                <span className="text-xs uppercase">Downloads</span>
                                <Download className="mr-2 ms-2 text-secondary" />
                                <span className="font-bold">{block.downloadCount}</span>
                            </div>
                        </CardHeader>
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
                        <CardFooter className="flex flex-wrap justify-center gap-4 mt-auto">
                            <a href={`/satisfactory/blueprint/${block.id}/download/sbp`}>
                                <Button variant="default">Télécharger .sbp</Button>
                            </a>
                            <a href={`/satisfactory/blueprint/${block.id}/download/sbpcfg`}>
                                <Button variant="neutral">Télécharger .sbpcfg (optionnel)</Button>
                            </a>
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