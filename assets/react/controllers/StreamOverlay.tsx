import * as React from "react";
import { Card } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

export default function StreamOverlay() {
    return (
        <Card className="flex flex-col justify-end bg-transparent shadow-xl w-[350px] h-[350px] shadow-main">
            <div className="flex justify-end mb-4 me-4">
                <Button className="w-fit">
                    Default
                </Button>
            </div>
        </Card>
    );
}
