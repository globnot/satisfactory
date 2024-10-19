import * as React from 'react';
import { Card } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Heart } from 'lucide-react';
import { useEffect, useState } from 'react';

const StreamOverlay = () => {
    const [subscriberCount, setSubscriberCount] = useState(0);
    const [targetSubs, setTargetSubs] = useState(10); // Initial target

    const fetchSubscriberCount = async () => {
        try {
            const response = await fetch('/twitch/overlay/webcam/subscriber-count');
            const data = await response.json();
            if (data.subscriberCount !== undefined) {
                setSubscriberCount(data.subscriberCount);
                setTargetSubs(Math.ceil((data.subscriberCount + 1) / 10) * 10);
            } else {
                console.error('Erreur dans les données reçues:', data.error);
            }
        } catch (error) {
            console.error('Erreur lors de la récupération des abonnés:', error);
        }
    };

    useEffect(() => {
        // Récupérer les données initialement
        fetchSubscriberCount();

        // Mettre en place un intervalle pour mettre à jour les données toutes les 10 secondes
        const interval = setInterval(fetchSubscriberCount, 30000); // 30000 ms = 10 secondes

        // Nettoyer l'intervalle lorsque le composant est démonté
        return () => clearInterval(interval);
    }, []);

    return (
        <Card className="flex flex-col justify-end bg-transparent shadow-xl w-[350px] h-[350px] shadow-main">
            <div className="flex justify-end mb-4 me-4">
                <Button className="text-2xl font-black w-fit bg-secondary">
                    {subscriberCount}/{targetSubs} SUBS
                    <Heart size={30} strokeWidth={2} className="ms-4" />
                </Button>
            </div>
        </Card>
    );
};

export default StreamOverlay;