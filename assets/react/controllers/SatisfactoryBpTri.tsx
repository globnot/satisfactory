import * as React from "react";

import { Button } from '@/components/ui/button';
import { ArrowDownNarrowWide, ArrowDownWideNarrow, ArrowUpNarrowWide, ArrowUpWideNarrow, } from "lucide-react";

export default function SatisfactoryBpTri({ currentSort, currentDirection, sortDownloadPath, sortDatePath }) {
    return (
        <div className="flex flex-wrap gap-4 mb-6 sorting-options">
            <Button
                variant="neutral"
                onClick={() => window.location.href = sortDownloadPath}
                className={currentSort === 'downloadCount' ? 'active' : ''}
            >
                Nombre de téléchargements
                {currentSort === 'downloadCount' && (
                    <span className="ms-1 text-secondary sort-indicator">
                        {currentDirection === 'DESC' ? <ArrowUpWideNarrow /> : <ArrowDownWideNarrow />}
                    </span>
                )}
            </Button>
            <Button
                variant="neutral"
                onClick={() => window.location.href = sortDatePath}
                className={currentSort === 'createdAt' ? 'active' : ''}
            >
                Date d'ajout
                {currentSort === 'createdAt' && (
                    <span className="ms-1 text-secondary sort-indicator">
                        {currentDirection === 'DESC' ? <ArrowUpWideNarrow /> : <ArrowDownWideNarrow />}
                    </span>
                )}
            </Button>
        </div>
    );
}