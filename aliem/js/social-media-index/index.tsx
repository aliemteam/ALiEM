import * as React from 'react';
import { render } from 'react-dom';
import SMIPlot from './smi-plot';
import SMITable from './smi-table';

import 'react-virtualized/styles.css';

export interface Rank {
    date: string;
    [siteName: string]: number | string;
}

export interface SMIData {
    data: Rank[];
    headings: string[];
    rows: string[][];
    rawData: any;
}

declare const __smi: SMIData;

const SocialMediaIndex = () => (
    <div>
        <h2 style={{ textAlign: 'center' }}>Top 25 Sites</h2>
        <SMIPlot data={__smi.data} />
        <SMITable rows={__smi.rows} headings={__smi.headings} rawData={__smi.rawData} />
    </div>
);

render(<SocialMediaIndex />, document.getElementById('smi-viz'));
