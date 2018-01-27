import * as React from 'react';

export default class XAxisLabel extends React.PureComponent<any, any> {
    render() {
        const { x, y, payload } = this.props;
        const date = new Date(payload.value).toLocaleDateString('en-US', {
            month: 'short',
            year: 'numeric',
        });
        return (
            <g transform={`translate(${x},${y - 10})`}>
                <text textAnchor="middle" fill="#666">
                    {date}
                </text>
            </g>
        );
    }
}
