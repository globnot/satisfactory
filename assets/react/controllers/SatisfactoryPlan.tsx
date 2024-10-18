import * as React from "react"
import { Carousel } from "@/components/ui/carousel"
import { Button } from '@/components/ui/button'
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'

interface Block {
    title: string;
    description: string;
    author: string;
    createdAt: string;
    updatedAt: string;
    comments: string[];
    downloadUrlSbp: string;
    downloadUrlSbpcfg: string;
    downloadCount: number;
    images: string[];
}

interface SatisfactoryPlanProps {
    blocks: Block[];
}

const SatisfactoryPlan: React.FC<SatisfactoryPlanProps> = ({ blocks }) => {
    return (
        <div>
            {blocks.map((block, index) => (
                <div key={index} className="block">
                    <Card className="w-[350px]">
                        <CardHeader>
                            <CardTitle>{block.title}</CardTitle>
                            <CardDescription>{block.description}</CardDescription>
                            <div className="flex items-center justify-between">
                                <span>by {block.author}</span>

                            </div>
                            <div className="flex items-center justify-between">
                                <span>Created at {block.createdAt}</span>
                                <span>Updated at {block.updatedAt}</span>
                            </div>
                            <div className="flex items-center justify-between">
                                <span>{block.comments.length} comments</span>
                                <span>{block.downloadCount} downloads</span>
                            </div>
                        </CardHeader>
                        <CardContent>
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
                        <CardFooter className="flex justify-between">
                            <Button variant="default">Download</Button>
                            <Button variant="default">Download</Button>
                        </CardFooter>
                    </Card>

                </div>
            ))}
        </div>
    )
}

export default SatisfactoryPlan
