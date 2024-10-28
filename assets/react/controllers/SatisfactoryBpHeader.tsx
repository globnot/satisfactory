import * as React from "react";

import { Terminal } from 'lucide-react'

import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'

export default function SatisfactoryBpHeader() {
    return (
        <div className="flex flex-wrap gap-4 mb-6 sorting-options">
            <Alert>
                <Terminal className="w-4 h-4" />
                <AlertTitle>Besoin d'aide pour mettre en place vos blueprints ou mieux les comprendre et les appréhender ?</AlertTitle>
                <AlertDescription>
                    Rendez-vous sur <a href="https://www.twitch.tv/globnot">https://www.twitch.tv/globnot</a> le stream de Globnot.
                </AlertDescription>
            </Alert>
        </div>
    );
}