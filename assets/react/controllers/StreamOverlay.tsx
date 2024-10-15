import * as React from 'react';
import { Card } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Heart } from 'lucide-react';

interface StreamOverlayProps {
    subscriberCount: number;
}

const StreamOverlay: React.FC<StreamOverlayProps> = ({ subscriberCount }) => {
    // Calculer le multiple de 10 supérieur
    const targetSubs = Math.ceil((subscriberCount + 1) / 10) * 10;

    return (
        <Card className="flex flex-col justify-end bg-transparent shadow-xl w-[350px] h-[350px] shadow-main">
            <div className="flex justify-end mb-4 me-4">
                <Button className="text-2xl font-black w-fit">
                    {subscriberCount}/{targetSubs} SUBS
                    <Heart size={30} strokeWidth={2} className="ms-4" />
                </Button>
            </div>
        </Card>
    );
};

export default StreamOverlay;
