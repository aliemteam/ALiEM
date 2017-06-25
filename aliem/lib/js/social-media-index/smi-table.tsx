import * as React from 'react';
import { AutoSizer, Column, Table } from 'react-virtualized';

interface Props {
    rows: string[][];
    headings: string[];
    rawData: any;
}

interface State {
    sortBy: string;
    rows: any;
    sortDirection: 'ASC' | 'DESC';
}

interface SortFuncParams {
    sortBy: string;
    sortDirection: 'ASC' | 'DESC';
}

export default class SMITable extends React.PureComponent<Props, State> {
    latestDateRaw: string;

    constructor(props) {
        super(props);
        this.latestDateRaw = this.props.headings[
            this.props.headings.length - 1
        ];
        const rows = [...this.props.rows]
            .map(row => {
                const r = row.reduce((prev, curr, i) => {
                    return { ...prev, [this.props.headings[i]]: curr };
                }, {});
                return { ...r, ...this.props.rawData[row[0]] };
            })
            .sort((a, b) => {
                const left = parseInt(a[this.latestDateRaw], 10);
                const right = parseInt(b[this.latestDateRaw], 10);
                if (left < right) return -1;
                if (left > right) return 1;
                return 0;
            });
        this.state = {
            rows: rows,
            sortBy: this.latestDateRaw,
            sortDirection: 'ASC',
        };
    }

    sortRows = ({ sortBy, sortDirection }: SortFuncParams) => {
        const LT = sortDirection === 'ASC' ? -1 : 1;
        const GT = LT * -1;
        const rows = [...this.state.rows].sort((a, b) => {
            let left, right;
            switch (sortBy) {
                case 'website':
                    left = a[sortBy].toLowerCase();
                    right = b[sortBy].toLowerCase();
                    break;
                case 'smi':
                    left = parseFloat(a[sortBy]);
                    right = parseFloat(b[sortBy]);
                    break;
                default:
                    left = parseInt(a[sortBy], 10);
                    right = parseInt(b[sortBy], 10);
            }
            if (left < right) return LT;
            if (left > right) return GT;
            return 0;
        });
        this.setState(prevState => ({
            ...prevState,
            rows,
            sortBy,
            sortDirection,
        }));
    };

    getCellData = ({ dataKey, rowData }) => {
        return this.props.rawData[rowData.website][dataKey];
    };

    renderURL = ({ cellData, rowData }) => {
        return (
            <a
                href={rowData.url}
                target="_blank"
                rel="noopener noreferrer"
                children={cellData}
            />
        );
    };

    render() {
        const { rows } = this.state;
        const latestDate = new Date(
            this.latestDateRaw
        ).toLocaleDateString('en-US', {
            month: 'long',
            year: 'numeric',
        });
        return (
            <div>
                <h2 style={{ textAlign: 'center' }}>
                    Latest Numbers ({latestDate})
                </h2>
                <AutoSizer disableHeight>
                    {({ width }) =>
                        <Table
                            width={width}
                            height={500}
                            headerHeight={50}
                            rowHeight={50}
                            sort={this.sortRows}
                            sortBy={this.state.sortBy}
                            sortDirection={this.state.sortDirection}
                            rowStyle={({ index }) =>
                                index % 2 === 0
                                    ? { backgroundColor: '#f5f5f5' }
                                    : {}}
                            rowCount={rows.length}
                            rowGetter={({ index }) => rows[index]}
                        >
                            <Column
                                flexGrow={1}
                                label="Rank"
                                dataKey={this.latestDateRaw}
                                width={10}
                            />
                            <Column
                                flexGrow={2}
                                flexShrink={0}
                                label="Site"
                                dataKey="website"
                                cellRenderer={this.renderURL}
                                width={100}
                            />
                            <Column
                                flexGrow={1}
                                width={40}
                                label="SMi"
                                dataKey="smi"
                                cellDataGetter={this.getCellData}
                            />
                            {width > 500 &&
                                <Column
                                    flexGrow={1}
                                    width={40}
                                    label="Alexa"
                                    dataKey="alexa"
                                    cellDataGetter={this.getCellData}
                                />}
                            {width > 500 &&
                                <Column
                                    flexGrow={1}
                                    width={40}
                                    label="Facebook"
                                    dataKey="facebook"
                                    cellDataGetter={this.getCellData}
                                />}
                            {width > 500 &&
                                <Column
                                    flexGrow={1}
                                    width={40}
                                    label="Twitter"
                                    dataKey="twitter"
                                    cellDataGetter={this.getCellData}
                                />}
                        </Table>}
                </AutoSizer>
                <p style={{ fontSize: '0.8em' }}>
                    * Sites without an Alexa Rank score were given the maximum
                    rank (the same as the lowest ranked website).
                </p>
            </div>
        );
    }
}
